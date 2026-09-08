<?php

namespace App\Libraries;

/**
 * HtmlSanitizer
 *
 * Sanitasi konten HTML dari rich text editor (WYSIWYG) untuk mencegah
 * Stored XSS. Menggunakan whitelist tag dan atribut yang diizinkan,
 * tanpa library eksternal — hanya menggunakan ekstensi DOM bawaan PHP.
 *
 * Digunakan untuk output konten artikel, deskripsi layanan,
 * dan konten lain yang berasal dari editor admin/WPA.
 */
class HtmlSanitizer
{
    /**
     * Tag HTML yang diizinkan beserta atribut yang boleh ada di setiap tag.
     * Semua atribut lain (event handler seperti onclick, onerror, onload, dll.)
     * akan dihapus otomatis.
     *
     * @var array<string, array<string>>
     */
    private static array $allowedTags = [
        // Struktur teks
        'p'          => ['class', 'style'],
        'br'         => [],
        'hr'         => ['class'],
        'span'       => ['class', 'style'],
        'div'        => ['class', 'style'],

        // Heading
        'h1'         => ['class', 'style', 'id'],
        'h2'         => ['class', 'style', 'id'],
        'h3'         => ['class', 'style', 'id'],
        'h4'         => ['class', 'style', 'id'],
        'h5'         => ['class', 'style', 'id'],
        'h6'         => ['class', 'style', 'id'],

        // Format teks
        'strong'     => ['class'],
        'b'          => ['class'],
        'em'         => ['class'],
        'i'          => ['class'],
        'u'          => ['class'],
        's'          => ['class'],
        'del'        => [],
        'ins'        => [],
        'mark'       => ['class'],
        'small'      => ['class'],
        'sub'        => [],
        'sup'        => [],
        'blockquote' => ['class', 'cite'],
        'code'       => ['class'],
        'pre'        => ['class'],
        'kbd'        => [],

        // List
        'ul'         => ['class', 'style'],
        'ol'         => ['class', 'style', 'start', 'type'],
        'li'         => ['class', 'style', 'value'],

        // Link — href disanitasi terpisah (hanya http/https/mailto)
        'a'          => ['href', 'title', 'target', 'rel', 'class'],

        // Gambar — src disanitasi terpisah
        'img'        => ['src', 'alt', 'title', 'width', 'height', 'class', 'style', 'loading'],

        // Tabel
        'table'      => ['class', 'style', 'border', 'cellpadding', 'cellspacing', 'width'],
        'thead'      => ['class'],
        'tbody'      => ['class'],
        'tfoot'      => ['class'],
        'tr'         => ['class', 'style'],
        'th'         => ['class', 'style', 'colspan', 'rowspan', 'scope'],
        'td'         => ['class', 'style', 'colspan', 'rowspan'],
        'caption'    => ['class'],

        // Media embed (hanya iframe dari domain terpercaya — divalidasi terpisah)
        'iframe'     => ['src', 'width', 'height', 'frameborder', 'allowfullscreen', 'allow', 'class', 'style', 'title', 'loading'],

        // Figure & caption
        'figure'     => ['class', 'style'],
        'figcaption' => ['class'],
    ];

    /**
     * Domain yang diizinkan untuk tag <iframe src>
     * @var array<string>
     */
    private static array $allowedIframeDomains = [
        'youtube.com',
        'www.youtube.com',
        'youtu.be',
        'player.vimeo.com',
        'vimeo.com',
        'docs.google.com',
        'drive.google.com',
        'www.canva.com',
        'canva.com',
        'slides.com',
        'www.slideshare.net',
    ];

    /**
     * Sanitasi string HTML menggunakan whitelist tag dan atribut.
     *
     * @param  string $html  HTML mentah dari editor
     * @return string        HTML yang sudah dibersihkan
     */
    public static function clean(string $html): string
    {
        if (empty(trim($html))) {
            return '';
        }

        // Gunakan DOMDocument PHP untuk parsing yang benar
        $dom = new \DOMDocument('1.0', 'UTF-8');

        // Suppress warnings dari HTML yang tidak sempurna (editor output sering tidak valid)
        libxml_use_internal_errors(true);

        // Bungkus dalam wrapper agar fragment HTML ter-parse dengan benar
        // mb_convert_encoding memastikan karakter UTF-8 di-handle dengan benar
        $dom->loadHTML(
            '<?xml encoding="UTF-8"><html><body>' . $html . '</body></html>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR
        );

        libxml_clear_errors();
        libxml_use_internal_errors(false);

        // Iterasi semua elemen dan bersihkan
        self::cleanNode($dom->documentElement);

        // Ambil konten dari <body> saja
        $body = $dom->getElementsByTagName('body')->item(0);
        if (!$body) {
            return '';
        }

        // Rekonstruksi HTML dari child nodes body
        $result = '';
        foreach ($body->childNodes as $child) {
            $result .= $dom->saveHTML($child);
        }

        return $result;
    }

    /**
     * Rekursif bersihkan node: hapus tag tidak diizinkan, bersihkan atribut berbahaya.
     */
    private static function cleanNode(\DOMNode $node): void
    {
        // Kumpulkan daftar child dulu (karena kita akan memodifikasi saat iterasi)
        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            if ($child->nodeType === XML_ELEMENT_NODE) {
                $tagName = strtolower($child->nodeName);

                if (!array_key_exists($tagName, self::$allowedTags)) {
                    // Tag tidak diizinkan — ganti dengan konten teks-nya saja (unwrap)
                    // Kecuali tag berbahaya: script, style, iframe tak valid — hapus seluruhnya
                    $dangerousTags = ['script', 'style', 'object', 'embed', 'applet', 'form', 'input', 'button', 'textarea', 'select', 'link', 'meta', 'base'];
                    if (in_array($tagName, $dangerousTags, true)) {
                        $node->removeChild($child);
                    } else {
                        // Unwrap: pindahkan children ke parent, hapus tag pembungkus
                        $grandChildren = [];
                        foreach ($child->childNodes as $gc) {
                            $grandChildren[] = $gc;
                        }
                        foreach ($grandChildren as $gc) {
                            $node->insertBefore($gc->cloneNode(true), $child);
                        }
                        $node->removeChild($child);
                    }
                } else {
                    // Tag diizinkan — bersihkan atributnya
                    self::cleanAttributes($child, $tagName);
                    // Rekursif ke children
                    self::cleanNode($child);
                }
            }
            // Text node dan comment node dibiarkan (text node aman, comment dihapus)
            elseif ($child->nodeType === XML_COMMENT_NODE) {
                $node->removeChild($child);
            }
        }
    }

    /**
     * Bersihkan atribut pada sebuah elemen:
     * - Hapus atribut yang tidak ada di whitelist
     * - Sanitasi nilai href dan src (cegah javascript: protocol)
     * - Validasi domain iframe
     */
    private static function cleanAttributes(\DOMElement $element, string $tagName): void
    {
        $allowedAttrs = self::$allowedTags[$tagName] ?? [];

        // Kumpulkan semua atribut yang ada
        $attrsToRemove = [];
        $attrsToSet    = [];

        for ($i = 0; $i < $element->attributes->length; $i++) {
            $attr      = $element->attributes->item($i);
            $attrName  = strtolower($attr->nodeName);
            $attrValue = $attr->nodeValue;

            // Hapus jika tidak ada di whitelist atau dimulai dengan 'on' (event handler)
            if (!in_array($attrName, $allowedAttrs, true) || str_starts_with($attrName, 'on')) {
                $attrsToRemove[] = $attrName;
                continue;
            }

            // Sanitasi href — tolak protokol berbahaya
            if ($attrName === 'href') {
                $clean = self::sanitizeUrl($attrValue);
                if ($clean === null) {
                    $attrsToRemove[] = $attrName;
                } else {
                    $attrsToSet[$attrName] = $clean;
                }
                continue;
            }

            // Sanitasi src untuk <img> — tolak protokol berbahaya, izinkan http/https/data:image
            if ($attrName === 'src' && $tagName === 'img') {
                $clean = self::sanitizeImgSrc($attrValue);
                if ($clean === null) {
                    $attrsToRemove[] = $attrName;
                } else {
                    $attrsToSet[$attrName] = $clean;
                }
                continue;
            }

            // Sanitasi src untuk <iframe> — hanya domain terpercaya
            if ($attrName === 'src' && $tagName === 'iframe') {
                $clean = self::sanitizeIframeSrc($attrValue);
                if ($clean === null) {
                    $attrsToRemove[] = $attrName;
                } else {
                    $attrsToSet[$attrName] = $clean;
                }
                continue;
            }

            // Paksa rel="noopener noreferrer" pada link yang target="_blank"
            if ($attrName === 'target' && $attrValue === '_blank') {
                $attrsToSet['rel'] = 'noopener noreferrer';
            }

            // Sanitasi nilai style — hapus expression() dan url() dengan protokol berbahaya
            if ($attrName === 'style') {
                $cleanStyle = self::sanitizeStyle($attrValue);
                if (empty($cleanStyle)) {
                    $attrsToRemove[] = $attrName;
                } else {
                    $attrsToSet[$attrName] = $cleanStyle;
                }
                continue;
            }
        }

        // Hapus atribut yang tidak diizinkan
        foreach ($attrsToRemove as $attrName) {
            $element->removeAttribute($attrName);
        }

        // Set atribut yang sudah disanitasi
        foreach ($attrsToSet as $name => $value) {
            $element->setAttribute($name, $value);
        }
    }

    /**
     * Sanitasi URL untuk href — hanya izinkan http, https, mailto, tel.
     * Kembalikan null jika protokol berbahaya.
     */
    private static function sanitizeUrl(string $url): ?string
    {
        $url = trim($url);
        if (empty($url)) return null;

        // Decode entitas HTML dan URL-encode ganda untuk mendeteksi bypass
        $decoded = html_entity_decode($url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $decoded = urldecode($decoded);
        // Hapus whitespace dan null bytes tersembunyi
        $decoded = preg_replace('/[\x00-\x1F\x7F\s]/u', '', $decoded);

        $scheme = strtolower(parse_url($decoded, PHP_URL_SCHEME) ?? '');

        // Hanya izinkan protokol aman
        if (!in_array($scheme, ['http', 'https', 'mailto', 'tel', ''], true)) {
            return null; // Blokir: javascript:, data:, vbscript:, dsb.
        }

        return $url;
    }

    /**
     * Sanitasi src untuk <img>.
     * Izinkan http/https dan data:image/* (base64 embed).
     */
    private static function sanitizeImgSrc(string $src): ?string
    {
        $src     = trim($src);
        $decoded = strtolower(preg_replace('/[\x00-\x1F\x7F\s]/u', '', html_entity_decode($src, ENT_QUOTES)));
        $scheme  = strtolower(parse_url($src, PHP_URL_SCHEME) ?? '');

        // Izinkan data:image/* untuk base64 embed
        if (str_starts_with($decoded, 'data:image/')) {
            // Pastikan format data URI valid: data:image/<type>;base64,<data>
            if (preg_match('/^data:image\/(png|jpe?g|gif|webp|svg\+xml);base64,[a-zA-Z0-9+\/=]+$/i', $src)) {
                return $src;
            }
            return null;
        }

        if (!in_array($scheme, ['http', 'https', ''], true)) {
            return null;
        }

        return $src;
    }

    /**
     * Sanitasi src untuk <iframe> — hanya domain yang ada di whitelist.
     */
    private static function sanitizeIframeSrc(string $src): ?string
    {
        $src    = trim($src);
        $scheme = strtolower(parse_url($src, PHP_URL_SCHEME) ?? '');
        $host   = strtolower(parse_url($src, PHP_URL_HOST) ?? '');

        if ($scheme !== 'https') {
            return null;
        }

        foreach (self::$allowedIframeDomains as $domain) {
            if ($host === $domain || str_ends_with($host, '.' . $domain)) {
                return $src;
            }
        }

        return null; // Domain tidak diizinkan
    }

    /**
     * Sanitasi nilai CSS inline style — hapus expression(), url() dengan protokol berbahaya,
     * dan properti yang bisa digunakan untuk serangan (behavior, -moz-binding, dsb.)
     */
    private static function sanitizeStyle(string $style): string
    {
        // Hapus komentar CSS
        $style = preg_replace('/\/\*.*?\*\//s', '', $style);

        // Hapus expression() — IE CSS injection
        $style = preg_replace('/expression\s*\(/i', '', $style);

        // Hapus url() yang berisi protokol berbahaya
        $style = preg_replace('/url\s*\(\s*["\']?\s*(javascript|vbscript|data:(?!image))[^)]*\)/i', '', $style);

        // Hapus properti berbahaya
        $dangerousProps = ['behavior', '-moz-binding', 'binding'];
        foreach ($dangerousProps as $prop) {
            $style = preg_replace('/' . preg_quote($prop, '/') . '\s*:[^;]*/i', '', $style);
        }

        return trim($style);
    }
}
