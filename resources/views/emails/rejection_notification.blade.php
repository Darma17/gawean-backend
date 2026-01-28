<!DOCTYPE html>
<html>
<head>
    <title>Pemberitahuan Penolakan Lamaran</title>
</head>
<body>
    <h1>Pemberitahuan Penolakan Lamaran</h1>

    <p>Halo <strong>{{ $appliedJob->user->nama }}</strong>,</p>

    <p>Kami mohon maaf untuk memberitahukan bahwa lamaran Anda untuk posisi <strong>{{ $appliedJob->job->title }}</strong> di perusahaan <strong>{{ $appliedJob->job->user->nama }}</strong> tidak dapat kami lanjutkan pada tahap ini.</p>

    <p>Terima kasih atas minat dan waktu yang telah Anda berikan untuk melamar pekerjaan di perusahaan kami. Kami menghargai partisipasi Anda dan berharap kesempatan untuk bekerja sama di masa depan.</p>

    <p><strong>Detail Lamaran:</strong></p>
    <ul>
        <li>Posisi: {{ $appliedJob->job->title }}</li>
        <li>Perusahaan: {{ $appliedJob->job->user->nama }}</li>
        <li>Lokasi: {{ $appliedJob->job->lokasi_kerja }}</li>
        <li>Tanggal Lamaran: {{ $appliedJob->created_at->format('d M Y') }}</li>
    </ul>

    <p>Jika Anda memiliki pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami.</p>

    <p>Terima kasih dan semoga sukses di pencarian pekerjaan Anda selanjutnya.</p>

    <p>Salam,<br>
    Tim Rekrutmen {{ $appliedJob->job->user->nama }}</p>
</body>
</html>