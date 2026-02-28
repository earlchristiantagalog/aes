// ── OTP digit-box logic ──────────────────────────────────────────
const boxes = document.querySelectorAll('.otp-digit');
const hidden = document.getElementById('otp-hidden');
const submitBtn = document.getElementById('submitBtn');

function syncHidden() {
    const val = [...boxes].map(b => b.value).join('');
    hidden.value = val;
    // Mark filled boxes
    boxes.forEach(b => b.classList.toggle('filled', b.value !== ''));
    // Enable button only when all 6 filled
    if (submitBtn) submitBtn.disabled = val.length < 6;
}

boxes.forEach((box, i) => {
    box.addEventListener('input', e => {
        // Allow only digits
        box.value = box.value.replace(/\D/g, '').slice(-1);
        syncHidden();
        if (box.value && i < boxes.length - 1) boxes[i + 1].focus();
    });

    box.addEventListener('keydown', e => {
        if (e.key === 'Backspace' && !box.value && i > 0) {
            boxes[i - 1].value = '';
            boxes[i - 1].focus();
            syncHidden();
        }
        if (e.key === 'ArrowLeft' && i > 0) boxes[i - 1].focus();
        if (e.key === 'ArrowRight' && i < boxes.length - 1) boxes[i + 1].focus();
    });

    // Handle paste on any box
    box.addEventListener('paste', e => {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData)
            .getData('text').replace(/\D/g, '').slice(0, 6);
        [...pasted].forEach((ch, j) => {
            if (boxes[j]) boxes[j].value = ch;
        });
        const next = Math.min(pasted.length, boxes.length - 1);
        boxes[next].focus();
        syncHidden();
    });
});

// Auto-focus first box
if (boxes.length) boxes[0].focus();

