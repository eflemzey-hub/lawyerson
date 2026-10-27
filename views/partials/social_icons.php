<?php
$socialItems = [
    ['url' => $socialFacebook ?? '', 'icon' => 'fab fa-facebook-f', 'label' => 'Facebook'],
    ['url' => $socialLinkedin ?? '', 'icon' => 'fab fa-linkedin-in', 'label' => 'LinkedIn'],
    ['url' => $socialTwitter ?? '', 'icon' => 'fab fa-twitter', 'label' => 'Twitter'],
    ['url' => $socialWebsite ?? '', 'icon' => 'fas fa-globe', 'label' => 'Website'],
];
$variant = $variant ?? 'header';
$headerIconStyle = 'width: 28px; height: 28px; transition: 0.3s; opacity: 0.8;';
?>
<?php foreach ($socialItems as $item): ?>
    <?php $href = normalize_social_url($item['url']); ?>
    <?php if ($href === '') continue; ?>
    <?php if ($variant === 'header'): ?>
        <a href="<?= htmlspecialchars($href) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= htmlspecialchars($item['label']) ?>" class="text-white bg-primary-law d-inline-flex align-items-center justify-content-center me-1" style="<?= $headerIconStyle ?>"><i class="<?= $item['icon'] ?>"></i></a>
    <?php else: ?>
        <a href="<?= htmlspecialchars($href) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= htmlspecialchars($item['label']) ?>" class="me-3 fs-5"><i class="<?= $item['icon'] ?>"></i></a>
    <?php endif; ?>
<?php endforeach; ?>
