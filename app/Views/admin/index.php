<?php $this->extend('layout') ?>
<?php $this->section('content') ?>
<div class="mainscreen">
    <div class="frame frame1 active" data-url="<?= base_url('admin/dashboard') ?>">
        <?php echo view('admin/dashboard'); ?>
    </div>
    <div class="frame frame2" data-url="<?= base_url('admin/roombook') ?>">
        <div>Loading...</div>
    </div>
    <div class="frame frame3" data-url="<?= base_url('admin/payment') ?>">
        <div>Loading...</div>
    </div>
    <div class="frame frame4" data-url="<?= base_url('admin/room') ?>">
        <div>Loading...</div>
    </div>
    <div class="frame frame5" data-url="<?= base_url('admin/staff') ?>">
        <div>Loading...</div>
    </div>
</div>
<script>
const btns = document.querySelectorAll('.pagebtn');
const frames = document.querySelectorAll('.frame');

const frameActive = function (manual) {
    btns.forEach((btn) => btn.classList.remove('active'));
    frames.forEach((frame) => frame.classList.remove('active'));

    btns[manual].classList.add('active');
    frames[manual].classList.add('active');

    const url = frames[manual].getAttribute('data-url');
    if (!frames[manual].innerHTML.trim() || frames[manual].innerHTML.includes('Loading...')) {
        frames[manual].innerHTML = '<div>Loading...</div>';
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(data => {
            frames[manual].innerHTML = data;
        })
        .catch(error => {
            console.error('Error loading content:', error);
            frames[manual].innerHTML = '<div class="alert alert-danger">Failed to load content. Please try again.</div>';
        });
    }
};

btns.forEach((btn, i) => {
    btn.addEventListener('click', () => frameActive(i));
});
</script>
<?php $this->endSection() ?>