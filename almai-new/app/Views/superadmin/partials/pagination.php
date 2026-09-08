<?php $pager->setSurroundCount(2) ?>

<?php if ($pager->hasPreviousPage()) : ?>
    <a href="<?= $pager->getPreviousPage() ?>" class="px-3 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition text-sm">
        <i class="fas fa-chevron-left"></i>
    </a>
<?php endif ?>

<?php foreach ($pager->links() as $link) : ?>
    <a href="<?= $link['uri'] ?>" class="px-3 py-2 rounded-lg text-sm <?= $link['active'] ? 'bg-accent text-black font-bold' : 'bg-white/10 hover:bg-white/20' ?>">
        <?= $link['title'] ?>
    </a>
<?php endforeach ?>

<?php if ($pager->hasNextPage()) : ?>
    <a href="<?= $pager->getNextPage() ?>" class="px-3 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition text-sm">
        <i class="fas fa-chevron-right"></i>
    </a>
<?php endif ?>
