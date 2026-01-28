<!DOCTYPE html>
<html>
<head>
    <title>Selamat! Anda Diterima</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #2c3e50; margin-bottom: 10px;">🎉 Selamat! 🎉</h1>
            <h2 style="color: #27ae60; margin-top: 0;">Anda Telah Diterima di Perusahaan Kami</h2>
        </div>

        <p style="font-size: 16px;">Halo <strong>{{ $appliedJob->user->nama }}</strong>,</p>

        <p>Dengan bangga dan sukacita, kami mengumumkan bahwa Anda telah <strong>DITERIMA</strong> untuk bergabung dengan tim kami di posisi <strong>{{ $appliedJob->job->title }}</strong>.</p>

        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #27ae60;">
            <h3 style="margin-top: 0; color: #2c3e50;">Detail Penerimaan:</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; width: 150px;">Posisi:</td>
                    <td style="padding: 8px 0;">{{ $appliedJob->job->title }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Perusahaan:</td>
                    <td style="padding: 8px 0;">{{ $appliedJob->job->user->nama }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Lokasi:</td>
                    <td style="padding: 8px 0;">{{ $appliedJob->job->lokasi_kerja }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Tipe Pekerjaan:</td>
                    <td style="padding: 8px 0;">{{ ucfirst($appliedJob->job->tipe) }}</td>
                </tr>
                @if($appliedJob->job->gaji)
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Gaji:</td>
                    <td style="padding: 8px 0;">{{ $appliedJob->job->gaji }} / Bulan</td>
                </tr>
                @endif
            </table>
        </div>

        <p>Tim kami sangat antusias untuk menyambut Anda sebagai bagian dari keluarga besar <strong>{{ $appliedJob->job->user->nama }}</strong>. Kami percaya bahwa pengalaman dan kemampuan Anda akan menjadi aset berharga bagi perusahaan kami.</p>

        <div style="background-color: #e8f5e8; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #27ae60;">
            <h4 style="margin-top: 0; color: #2c3e50;">Langkah Selanjutnya:</h4>
            <p style="margin-bottom: 5px;">1. <strong>Persiapan Dokumen</strong>: Siapkan dokumen-dokumen yang diperlukan untuk proses onboarding</p>
            <p style="margin-bottom: 5px;">2. <strong>Kontak HR</strong>: Tim HR kami akan segera menghubungi Anda untuk informasi lebih lanjut</p>
            <p style="margin-bottom: 0;">3. <strong>Onboarding</strong>: Ikuti proses orientasi dan pelatihan yang akan dijadwalkan</p>
        </div>

        <p>Jika Anda memiliki pertanyaan atau memerlukan informasi tambahan, jangan ragu untuk menghubungi tim rekrutmen kami di:</p>
        <p><strong>Email:</strong> {{ $appliedJob->job->user->email }}</p>
        <p><strong>Telepon:</strong> {{ $appliedJob->job->user->nomor_telepon }}</p>

        <div style="text-align: center; margin: 30px 0; padding: 20px; background-color: #f8f9fa; border-radius: 8px;">
            <p style="font-size: 18px; font-weight: bold; color: #27ae60; margin: 0;">
                Selamat Bergabung dan Selamat Bekerja! 🚀
            </p>
        </div>

        <p style="text-align: center; color: #7f8c8d; font-size: 14px;">
            Hormat kami,<br>
            <strong>Tim Rekrutmen {{ $appliedJob->job->user->nama }}</strong>
        </p>

        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">

        <p style="text-align: center; color: #95a5a6; font-size: 12px;">
            Email ini dikirim secara otomatis oleh sistem rekrutmen.<br>
            Jika Anda merasa ini adalah kesalahan, silakan abaikan email ini.
        </p>
    </div>
</body>
</html>