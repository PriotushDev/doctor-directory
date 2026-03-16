<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Appointment Confirmation</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; background:#f4f6f9; margin:0; padding:0;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9; padding:40px 0;">
<tr>
<td align="center">

<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:8px; overflow:hidden;">

<tr>
<td style="background:#4A90E2; color:white; text-align:center; padding:30px;">
<h1 style="margin:0;">Appointment Confirmed</h1>
</td>
</tr>

<tr>
<td style="padding:30px; color:#333; line-height:1.6;">

<p>হ্যালো <strong>{{ $appointment->user->name }}</strong>,</p>

<p>
আপনার অ্যাপয়েন্টমেন্ট সফলভাবে বুক করা হয়েছে।  
নিচে আপনার অ্যাপয়েন্টমেন্টের বিস্তারিত তথ্য দেওয়া হলো।
</p>

<table width="100%" cellpadding="10" cellspacing="0" style="background:#f9f9f9; border:1px solid #eee; border-radius:6px;">

<tr>
<td><strong>Doctor</strong></td>
<td align="right">{{ $appointment->doctor->name ?? 'N/A' }}</td>
</tr>

<tr>
<td><strong>Date</strong></td>
<td align="right">{{ $appointment->appointment_date }}</td>
</tr>

<tr>
<td><strong>Time</strong></td>
<td align="right">{{ $appointment->appointment_time }}</td>
</tr>

<tr>
<td><strong>Notes</strong></td>
<td align="right">{{ $appointment->notes ?? 'N/A' }}</td>
</tr>

</table>

<p style="margin-top:20px;">
যদি কোনো পরিবর্তন করতে চান তাহলে আপনার dashboard থেকে manage করতে পারবেন।
</p>

<p style="text-align:center;">
<a href="#" 
style="background:#4A90E2; color:white; padding:12px 28px; text-decoration:none; border-radius:5px; font-weight:bold;">
View Appointment
</a>
</p>

</td>
</tr>

<tr>
<td style="background:#f1f1f1; text-align:center; padding:20px; font-size:13px; color:#777;">
<p>© {{ date('Y') }} Doctor Directory</p>
<p>Support: support@example.com | Phone: +880-1XXXXXXXXX</p>
</td>
</tr>

</table>

</td>
</tr>
</table>

</body>
</html>