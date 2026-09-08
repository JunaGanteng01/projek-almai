<?php $pager->setSurroundCount(2) ?>
<nav aria-label="Page navigation">
    <ul class="flex items-center justify-center space-x-1">
    <?php if ($pager->hasPrevious()) : ?>
        <li>
            <a href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>" class="px-3 py-2 text-sm font-medium text-gray-400 bg-white/5 border border-white/10 rounded-lg hover:bg-white/10 hover:text-white transition">
                <i class="fas fa-angle-double-left"></i>
            </a>
        </li>
        <li>
            <a href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>" class="px-3 py-2 text-sm font-medium text-gray-400 bg-white/5 border border-white/10 rounded-lg hover:bg-white/10 hover:text-white transition">
                <i class="fas fa-angle-left"></i>
            </a>
        </li>
    <?php endif ?>

    <?php foreach ($pager->links() as $link) : ?>
        <li>
            <a href="<?= $link['uri'] ?>" class="px-4 py-2 text-sm font-medium <?= $link['active'] ? 'text-black bg-accent border-accent' : 'text-gray-400 bg-white/5 border-white/10 hover:bg-white/10 hover:text-white' ?> border rounded-lg transition">
                <?= $link['title'] ?>
            </a>
        </li>
    <?php endforeach ?>

    <?php if ($pager->hasNext()) : ?>
        <li>
            <a href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>" class="px-3 py-2 text-sm font-medium text-gray-400 bg-white/5 border border-white/10 rounded-lg hover:bg-white/10 hover:text-white transition">
                <i class="fas fa-angle-right"></i>
            </a>
        </li>
        <li>
            <a href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>" class="px-3 py-2 text-sm font-medium text-gray-400 bg-white/5 border border-white/10 rounded-lg hover:bg-white/10 hover:text-white transition">
                <i class="fas fa-angle-double-right"></i>
            </a>
        </li>
    <?php endif ?>
    </ul>
</nav>
