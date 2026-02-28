<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- include summernote css/js -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#productDescription').summernote({
            placeholder: 'Detailed product description with features and specifications...',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });

    $(document).ready(function() {
        // 1. Initialize Summernote
        $('#summernote').summernote({
            placeholder: 'Content will be auto-filled based on reason, or you can type manually...',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });

        const templates = {

            "Medical/Sick": `
        <div class="letter-content">
            <p>Dear Supervisor,</p>

            <p>I respectfully inform you that I am unable to report for work today due to a sudden illness. The symptoms developed unexpectedly and have significantly affected my ability to perform my duties effectively.</p>

            <p>To ensure proper recovery and avoid any further complications, I will be seeking medical consultation. Should it be required, I will provide a medical certificate as verification of my condition.</p>

            <p>I sincerely apologize for any inconvenience my absence may cause and appreciate your understanding. I expect to resume work as soon as I am medically fit to do so.</p>

            <p>Respectfully yours,</p>
        </div>
    `,

            "Personal Matter": `
        <div class="letter-content">
            <p>Dear Supervisor,</p>

            <p>I am writing to formally request an excuse from work today due to an urgent personal matter that requires my immediate attention. This situation is unavoidable and demands my presence.</p>

            <p>I assure you that I will attend to my responsibilities promptly upon my return and ensure that any pending tasks are properly managed.</p>

            <p>Thank you for your understanding and consideration.</p>

            <p>Respectfully yours,</p>
        </div>
    `,

            "Vacation": `
        <div class="letter-content">
            <p>Dear Supervisor,</p>

            <p>I would like to formally request vacation leave for the specified date(s). This leave will allow me time to rest and attend to personal matters.</p>

            <p>I have coordinated with my colleagues to ensure that my duties and responsibilities will be properly covered during my absence to avoid disruption of operations.</p>

            <p>Thank you for your approval and continued support.</p>

            <p>Respectfully yours,</p>
        </div>
    `,

            "Emergency": `
        <div class="letter-content">
            <p>Dear Supervisor,</p>

            <p>Please accept this letter as formal notice that I am unable to report to work due to an unforeseen emergency that requires my immediate attention.</p>

            <p>Given the urgency of the situation, I must prioritize resolving this matter. I will keep you informed regarding my status and expected return.</p>

            <p>I sincerely apologize for any inconvenience this may cause and appreciate your understanding during this time.</p>

            <p>Respectfully yours,</p>
        </div>
    `
        };


        // 3. Listen for Reason Selection
        $('#reasonSelect').on('change', function() {
            const selectedReason = $(this).val();
            if (templates[selectedReason]) {
                // Inject template into Summernote
                $('#summernote').summernote('code', templates[selectedReason]);
            } else {
                $('#summernote').summernote('code', '');
            }
        });

        // 4. Handle Printing Logic (Same as before)
        $('#absenceForm').on('submit', function() {
            const iframe = document.getElementById('print_frame');
            iframe.onload = function() {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            };
        });
    });
</script>

<!-- Custom JS -->
<script src="assets/js/index.js"></script>
<script src="assets/js/products.js"></script>
<script src="assets/js/fetch_icon.js"></script>
<script src="assets/js/settings-improved.js"></script>
<script src="asstes/js/staff.js"></script>
<script src="assets/js/warehouse.js"></script>

</body>

</html>