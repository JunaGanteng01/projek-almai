<div class="bg-[#111] border border-white/10 rounded-xl p-6 mb-6">
    <h3 class="font-bold mb-4">Partner & Media</h3>
    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-2">Pilih WPA</label>
                <select name="wpa_id" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    <option value="">-- Pilih WPA --</option>
                    <?php if (isset($wpaList) && is_array($wpaList)): ?>
                        <?php foreach ($wpaList as $wpa): ?>
                            <option value="<?= $wpa['id'] ?>"><?= esc($wpa['name']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Pilih CWPA</label>
                <select name="cwpa_id" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    <option value="">-- Pilih CWPA --</option>
                    <?php if (isset($cwpaList) && is_array($cwpaList)): ?>
                        <?php foreach ($cwpaList as $cwpa): ?>
                            <option value="<?= $cwpa['id'] ?>"><?= esc($cwpa['name']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        <div>
            <label class="block text-sm text-gray-400 mb-2">Media Thumnail</label>
            <input type="file" name="thumbnail" accept="image/*" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-2 text-sm">
        </div>
    </div>
</div>
