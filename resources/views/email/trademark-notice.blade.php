<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>{{ $wordmark ?? 'Trademark' }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,Helvetica,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:20px 0;">
    <tr>
      <td align="center">
        <table width="680" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
          
          <!-- Header -->
          <tr>
            <td style="padding:16px 18px;background:#fff7ed;border-bottom:1px solid #fde68a;">
              <div style="font-size:13px;color:#92400e;font-weight:bold;">
                ⚠️ ACTION REQUIRED: VERIFICATION APPOINTMENT
              </div>
              <div style="font-size:12px;color:#92400e;margin-top:4px;">
                Examining attorney appointment scheduled - failure to attend may result in abandonment
              </div>
            </td>
          </tr>

          <!-- Intro -->
          <tr>
            <td style="padding:18px;">
              <div style="font-size:14px;color:#111827;margin-bottom:10px;">Dear Applicant,</div>

              <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;color:#111827;">
                <tr><td style="padding:6px 0;"><b>Wordmark:</b> {{ $wordmark }}</td></tr>
                <tr><td style="padding:6px 0;"><b>Serial Number:</b> {{ $serial }}</td></tr>
                <tr><td style="padding:6px 0;"><b>Legal Entity Type:</b> {{ $entity }}</td></tr>
                <tr><td style="padding:6px 0;"><b>Owner Name:</b> {{ trim(preg_replace('/\s*\(.*$/', '', $name)) }}</td></tr>
                <tr><td style="padding:6px 0;"><b>Address:</b> {{ $address }}</td></tr>
              </table>

              <div style="margin-top:14px;background:#ecfdf5;border:1px solid #bbf7d0;padding:12px;border-radius:6px;">
                <div style="font-weight:bold;color:#065f46;margin-bottom:6px;">✓ Application Status Update</div>
                <div style="font-size:13px;color:#065f46;line-height:1.5;">
                  Your trademark application is currently live in the USPTO system and is pending examination. A required amendment has been identified, and the application has therefore been forwarded for verification and review by an assigned Examining Attorney.
                  <br/>
                  <br/>
                  The application has been formally assigned to an Examining Attorney from the relevant law office and will proceed once the review of the amendment is completed.
                </div>
              </div>
            </td>
          </tr>

          <!-- Appointment banner -->
          <tr>
            <td style="padding:0 18px 18px;">
              <div style="background:#b91c1c;color:#fff;padding:12px 12px;border-radius:6px 6px 0 0;font-weight:bold;">
                📞 SCHEDULED VERIFICATION APPOINTMENT
                <br/>
                You are required to call the examining attorney at the scheduled time below
              </div>
              <div style="background:#16a34a;color:#fff;padding:10px 12px;border-radius:0 0 6px 6px;font-size:16px;font-weight:bold;">
                {{ $direct_phone }}
                
              </div>

              <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:12px;font-size:13px;color:#111827;">
                <tr><td style="padding:4px 0;"><b>Examining Attorney:</b> {{ $examining_attorney }}</td></tr>
                <tr><td style="padding:4px 0;"><b>Direct Phone:</b> {{ $direct_phone }}</td></tr>
                <tr><td style="padding:4px 0;"><b>Date:</b> {{ $appointment_date }}</td></tr>
                <tr><td style="padding:4px 0;"><b>Appointment Time:</b> {{ $appointment_time }}</td></tr>
                <tr><td style="padding:4px 0;"><b>Appointment Number:</b> {{ $appointment_number }}</td></tr>
              </table>
            </td>
          </tr>

          <!-- Requirements -->
          <tr>
            <td style="padding:0 18px 18px;">
              <div style="font-weight:bold;color:#1f2937;margin-bottom:8px;">Verification Process Requirements</div>
                 <div style="font-size:13px;line-height:1.5;color:#1f2937;">
                    The verification procedure includes the following elements that will be discussed during your appointment:
                  </div>
                
              <div style="background:#eff6ff;border:1px solid #bfdbfe;padding:12px;border-radius:6px;">
                <div style="font-weight:bold;margin-bottom:6px;">📋 Required Verification Components</div>

                <div style="margin-bottom:10px;">
                  <div style="font-weight:bold;">Owner's Verification</div>
                  <div style="font-size:13px;line-height:1.5;color:#1f2937;">
                    The examining attorney will verify owner details including name, serial number(s), address, duration of business use, goods & services offered under the mark, and your position in the company. Providing the EIN number could save time during the verification process.
                  </div>
                </div>

                <div style="margin-bottom:10px;">
                  <div style="font-weight:bold;">Business Information Verification</div>
                  <div style="font-size:13px;line-height:1.5;color:#1f2937;">
                    Verification includes owner details (name, email, phone number), serial number of your trademark application, assigned categories, business description, and mode of platform for generating business.
                  </div>
                </div>

                <div style="margin-bottom:10px;">
                  <div style="font-weight:bold;">Prescribed Obligation(s)</div>
                  <div style="font-size:13px;line-height:1.5;color:#1f2937;">
                    If required, the examining attorney will discuss any prescribed federal obligations during the verification call.
                  </div>
                </div>

                <div>
                  <div style="font-weight:bold;">Conflict or Infringement Review</div>
                  <div style="font-size:13px;line-height:1.5;color:#1f2937;">
                    If there is any conflict or infringement dispute against your application, the examining attorney will inform you accordingly during the call.
                  </div>
                </div>
              </div>

              <div style="margin-top:14px;background:#fef2f2;border:1px solid #fecaca;padding:12px;border-radius:6px;">
                <div style="font-weight:bold;color:#991b1b;margin-bottom:6px;">⚠️ CRITICAL: Appointment Compliance Requirements</div>
                <ul style="margin:0;padding-left:18px;color:#991b1b;font-size:13px;line-height:1.6;">
                  <li><b>YOU ARE REQUIRED TO CALL</b> the examining attorney directly at the scheduled time. This is not an incoming call - you must initiate contact. If you fail to make the call, you will be granted ONE FINAL OPPORTUNITY to reschedule.</li>
                  <li><b>If you fail to make the call,</b> you will be granted ONE FINAL OPPORTUNITY to reschedule.</li>
                  <li><b>Failing to complete this verification</b> may result in your application being marked as abandoned or rejected.</li>
                  <li><b>If you cannot attend</b>, you must reply to this email immediately to provide your availability for the call to be rescheduled.</li>
                  <li><b>This call will play an important role</b> for the United States Patent & Trademark Office (TTAB - Trademark Trial Appeal Board). If you answer all questions truthfully, the examining attorney will forward your application to publication, enabling full registration.</li>
                </ul>
              </div>

              <div style="margin-top:14px;background:#fffbeb;border:1px solid #fde68a;padding:10px;border-radius:6px;color:#92400e;font-size:13px;">
                🔔 <b>Acknowledgement Required:</b> Please acknowledge this email once received.
              </div>

              <!-- Compliance footer -->
              <div style="margin-top:14px;font-size:12px;color:#6b7280;line-height:1.5;">
                <b>Important Notes:</b> <br/> <b>Interaction & Monetary Commitment:</b> This verification represents an interaction and monetary commitment toward the application that will be documented under your possession with the USPTO. 
                <br/> <b>Federal Reservation Process:</b> Successful verification allows authorities to reserve the mark with all Secretary of State(s) in the US, marking your business as a certified business under federal protection.
                <br/> <b>Publication Pathway:</b> Upon successful verification, your application will be forwarded to publication, which is the pathway to full registration.
              </div>
            </td>
          </tr>
        
          <!-- Footer -->
          <tr>
            <td style="padding:16px 20px;background:#111827;color:#e5e7eb;font-size:12px;text-align:center;">
              United States Patent and Trademark Office The United States Patent and Trademark Office (USPTO) is the federal agency for granting U.S. patents and registering trademarks. In doing this, the USPTO fulfills the mandate of Article I, Section 8, Clause 8, of the Constitution that the legislative branch "promote the Progress of Science and useful Arts, by securing for limited Times to Authors and Inventors the exclusive Right to their respective Writings and Discoveries." The USPTO registers trademarks based on the commerce clause of the Constitution (Article I, Section 8, Clause 3). Under this system of protection, American industry has flourished. New products have been invented, new uses for old ones discovered, and employment opportunities created for millions of Americans. The USPTO advises the president of the United States, the secretary of commerce, and U.S. government agencies on intellectual property (IP) policy, protection, and enforcement; and promotes stronger and more effective IP protection around the world. The USPTO furthers effective IP protection for U.S. innovators and entrepreneurs worldwide by working with other agencies to secure strong IP provisions in free trade and other international agreements. United States Patent and Trademark Office U.S. DEPARTMENT OF COMMERCE 600 Dulany Street, Alexandria, Virginia 22314
            </td>
          </tr>
            <div>
          <div style="font-weight:bold;">Regards,</div>
          <div style="font-size:13px;line-height:1.5;color:#1f2937;">
            <b>United States Patent and Trademark Office</b>
            Intellectual Property Office
            600 Dulany Street
            Alexandria, Virginia 22314
          </div>
        </div>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
