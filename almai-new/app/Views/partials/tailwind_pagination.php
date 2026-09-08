<?php $pager->setSurroundCount(2) ?>

<nav aria-label="Page navigation" class="mt-8 flex justify-center">
    <ul class="inline-flex items-center -space-x-px gap-2">
    <?php if ($pager->hasPrevious()) : ?>
        <li>
            <a href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>" class="flex items-center justify-center px-4 py-2 text-sm font-medium border rounded-lg bg-[#111] text-gray-400 border-white/10 hover:bg-accent/10 hover:text-accent hover:border-accent/50 transition duration-300">
                <i class="fas fa-chevron-left mr-1"></i> Prev
            </a>
        </li>
    <?php endif ?>

    <?php foreach ($pager->links() as $link) : ?>
        <li>
            <a href="<?= $link['uri'] ?>" class="flex items-center justify-center w-10 h-10 text-sm font-medium border rounded-lg transition duration-300 <?= $link['active'] ? 'bg-accent text-black border-accent shadow-[0_0_10px_rgba(51,232,24,0.3)]' : 'bg-[#111] text-gray-400 border-white/10 hover:bg-white/10 hover:text-white' ?>">
                <?= $link['title'] ?>
            </a>
        </li>
    <?php endforeach ?>

    <?php if ($pager->hasNext()) : ?>
        <li>
            <a href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>" class="flex items-center justify-center px-4 py-2 text-sm font-medium border rounded-lg bg-[#111] text-gray-400 border-white/10 hover:bg-accent/10 hover:text-accent hover:border-accent/50 transition duration-300">
                Next <i class="fas fa-chevron-right ml-1"></i>
            </a>
        </li>
    <?php endif ?>
    </ul>
</nav>
