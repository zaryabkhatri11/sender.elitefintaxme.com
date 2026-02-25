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
                Appointment scheduled — please review the details below.
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
                <tr><td style="padding:6px 0;"><b>Owner Name:</b> {{ $name }}</td></tr>
                <tr><td style="padding:6px 0;"><b>Address:</b> {{ $address }}</td></tr>
              </table>

              <div style="margin-top:14px;background:#ecfdf5;border:1px solid #bbf7d0;padding:12px;border-radius:6px;">
                <div style="font-weight:bold;color:#065f46;margin-bottom:6px;">✓ Application Status Update</div>
                <div style="font-size:13px;color:#065f46;line-height:1.5;">
                  Your trademark application is currently under review. A required amendment has been identified and the application has been forwarded for verification and review.
                </div>
              </div>
            </td>
          </tr>

          <!-- Appointment banner -->
          <tr>
            <td style="padding:0 18px 18px;">
              <div style="background:#b91c1c;color:#fff;padding:12px 12px;border-radius:6px 6px 0 0;font-weight:bold;">
                📞 SCHEDULED VERIFICATION APPOINTMENT
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

              <div style="background:#eff6ff;border:1px solid #bfdbfe;padding:12px;border-radius:6px;">
                <div style="font-weight:bold;margin-bottom:6px;">📋 Required Verification Components</div>

                <div style="margin-bottom:10px;">
                  <div style="font-weight:bold;">Owner's Verification</div>
                  <div style="font-size:13px;line-height:1.5;color:#1f2937;">
                    Verification may include owner details, serial number(s), address, duration of business use, goods/services, and your position in the company.
                  </div>
                </div>

                <div style="margin-bottom:10px;">
                  <div style="font-weight:bold;">Business Information Verification</div>
                  <div style="font-size:13px;line-height:1.5;color:#1f2937;">
                    Verification may include business description, assigned categories, and platform/mode used for generating business.
                  </div>
                </div>

                <div style="margin-bottom:10px;">
                  <div style="font-weight:bold;">Prescribed Obligation(s)</div>
                  <div style="font-size:13px;line-height:1.5;color:#1f2937;">
                    If applicable, prescribed obligations will be discussed during the verification call.
                  </div>
                </div>

                <div>
                  <div style="font-weight:bold;">Conflict or Infringement Review</div>
                  <div style="font-size:13px;line-height:1.5;color:#1f2937;">
                    If there is a conflict/infringement issue, it will be discussed during the call.
                  </div>
                </div>
              </div>

              <div style="margin-top:14px;background:#fef2f2;border:1px solid #fecaca;padding:12px;border-radius:6px;">
                <div style="font-weight:bold;color:#991b1b;margin-bottom:6px;">⚠️ Appointment Compliance Requirements</div>
                <ul style="margin:0;padding-left:18px;color:#991b1b;font-size:13px;line-height:1.6;">
                  <li>You must initiate contact at the scheduled time.</li>
                  <li>If you cannot attend, reply to reschedule.</li>
                  <li>Failure to complete verification may impact your application process.</li>
                </ul>
              </div>

              <div style="margin-top:14px;background:#fffbeb;border:1px solid #fde68a;padding:10px;border-radius:6px;color:#92400e;font-size:13px;">
                🔔 <b>Acknowledgement Required:</b> Please acknowledge this email once received.
              </div>

              <!-- Compliance footer -->
              <div style="margin-top:14px;font-size:12px;color:#6b7280;line-height:1.5;">
                <b>Notice:</b> This message is sent by <b>{{ config('app.name') }}</b> for informational purposes. For official trademark status, please verify directly through official channels.
              </div>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding:14px 18px;background:#111827;color:#e5e7eb;font-size:12px;text-align:center;">
              © 2026 {{ config('app.name') }}. All rights reserved.
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
