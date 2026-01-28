<!DOCTYPE html>
<html>
<head>
    <title>Undangan Interview</title>
</head>
<body>
    <h1>Selamat! Anda Lolos ke Tahap Interview</h1>
    
    <p>Halo <strong>{{ $appliedJob->user->nama }}</strong>,</p>
    
    <p>Selamat! Anda telah lolos seleksi awal untuk posisi <strong>{{ $appliedJob->job->title }}</strong> di perusahaan <strong>{{ $appliedJob->job->user->nama }}</strong>.</p>
    
    <p>Tim kami sangat tertarik dengan profil Anda dan ingin mengundang Anda untuk mengikuti tahap interview selanjutnya.</p>
    
    <p><strong>Detail Pekerjaan:</strong></p>
    <ul>
        <li>Posisi: {{ $appliedJob->job->title }}</li>
        <li>Perusahaan: {{ $appliedJob->job->user->nama }}</li>
        <li>Lokasi: {{ $appliedJob->job->lokasi_kerja }}</li>
        <li>Tipe: {{ $appliedJob->job->tipe }}</li>
    </ul>
    
    <p>Informasi lebih lanjut mengenai jadwal dan cara interview akan segera kami kirimkan melalui email ini atau kontak Anda.</p>
    
    <p>Terima kasih atas minat dan partisipasi Anda. Kami tunggu konfirmasi Anda!</p>
    
    <p>Salam,<br>
    Tim Rekrutmen {{ $appliedJob->job->user->nama }}</p>
</body>
</html>