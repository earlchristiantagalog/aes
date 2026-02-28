<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $request_date = $_POST['request_date'];
  $reason       = $_POST['reason'];
  $message      = $_POST['message'];
  $department   = $_SESSION['dept'] ?? 'N/A';
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="UTF-8">
    <script>
      window.onload = function() {
        window.print();
      };
    </script>
    <style>
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }

      @media print {

        html,
        body {
          height: 100%;
        }

        body {
          -webkit-print-color-adjust: exact;
          print-color-adjust: exact;
        }

        .no-print {
          display: none;
        }
      }

      body {
        font-family: Arial, sans-serif;
        color: #000;
        background: #fff;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
      }

      /* ─── HEADER ─── */
      .letterhead {
        width: 100%;
        text-align: center;
        padding: 24px 40px 16px 40px;
      }

      .lh-logo {
        height: 64px;
        width: auto;
        margin-bottom: 8px;
      }

      .lh-info {
        font-size: 9.5pt;
        line-height: 1.7;
        color: #111;
      }

      /* Horizontal rule under header */
      .lh-rule {
        border: none;
        border-top: 1px solid #000;
        margin: 0 40px;
      }

      /* ─── MAIN BODY ─── */
      .page-body {
        flex: 1;
        position: relative;
        padding: 32px 50px 28px 50px;
      }

      /* Faded watermark */
      .watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 380px;
        opacity: 0.06;
        pointer-events: none;
        z-index: 0;
      }

      .content-wrapper {
        position: relative;
        z-index: 1;
      }

      /* ─── FIELD ROWS ─── */
      .section {
        margin-bottom: 18px;
        font-size: 12pt;
      }

      .label {
        font-weight: bold;
      }

      .letter-title {
        display: block;
        text-align: center;
        font-size: 17pt;
        /* Make it bigger */
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 20px;
      }

      /* .letter-content p {
        text-indent: 40px;
        margin-bottom: 14px;
        line-height: 1.6;
      } */

      /* ─── CONTENT AREA ─── */
      .content-area {
        padding: 10px 0;
        min-height: 360px;
        margin-top: 6px;
        font-size: 12pt;
        line-height: 1.6;
      }

      /* ─── SIGNATURE SECTION ─── */
      .signature-section {
        margin-top: 60px;
      }

      .sig-block {
        margin-bottom: 28px;
      }

      .sig-label {
        font-weight: bold;
        font-size: 12pt;
        margin-bottom: 30px;
      }

      .sig-line {
        border-top: 1.5px solid #000;
        width: 180px;
        margin-bottom: 4px;
      }

      .sig-sublabel {
        font-weight: bold;
        font-size: 11pt;
        text-transform: uppercase;
      }

      /* ─── FOOTER ─── */
      .footer-rule {
        border: none;
        border-top: 1px solid #000;
        margin: 0 40px;
      }

      .footer {
        width: 100%;
        display: flex;
        align-items: center;
        padding: 16px 40px;
        gap: 16px;
      }

      .footer-logo {
        height: 48px;
        width: auto;
      }

      .footer-info {
        font-size: 9pt;
        line-height: 1.65;
        color: #111;
      }
    </style>
  </head>

  <body>

    <!-- HEADER -->
    <div class="letterhead">
      <img src="assets/AES.png" alt="AES Logo" class="lh-logo"><br>
      <div class="lh-info">
        AES | Malunhaw St. | Consolacion, Cebu 6001<br>
        Phone: 09940823693 | Email: info@aes.com |<br>
        www.aes.com
      </div>
    </div>

    <hr class="lh-rule">

    <!-- PAGE BODY -->
    <div class="page-body">

      <!-- Watermark -->
      <img src="assets/AES.png" alt="" class="watermark">

      <div class="content-wrapper">

        <div class="section">
          <span class="letter-title">
            LETTER FOR <?php echo strtoupper(htmlspecialchars($reason)); ?>
          </span>
        </div>

        <div class="section">
          <span class="label">DATE:</span> <?php echo htmlspecialchars($request_date) ?>
        </div>

        <div class="section">
          <div class="content-area">
            <?php echo $message ?>
          </div>
        </div>

        <!-- SIGNATURES -->
        <div class="signature-section">
          <div class="sig-block">
            <div class="sig-label">NOTED BY:</div>
            <div class="sig-line"></div>
            <div class="sig-sublabel"><?php echo htmlspecialchars($department) ?></div>
          </div>

          <div class="sig-block">
            <div class="sig-label">REVIEW BY:</div>
            <div class="sig-line"></div>
            <div class="sig-sublabel">OWNER</div>
          </div>
        </div>

      </div>
    </div>

    <!-- FOOTER -->
    <hr class="footer-rule">

    <div class="footer">
      <img src="assets/AES.png" alt="AES Logo" class="footer-logo">
      <div class="footer-info">
        AES | Malunhaw St. | Consolacion, Cebu 6001<br>
        Phone: 09940823693 | Email: info@aes.com |<br>
        www.aes.com
      </div>
    </div>

  </body>

  </html>
<?php } ?>