<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar member prototype</title>
</head>
<body>
    <h2>Daftar Member</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No HP</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($members as $member): ?>
                <tr>
                    <td><?= $member['ID_Member']; ?></td>
                    <td><?= $member['Nama_Member']; ?></td>
                    <td><?= $member['Email']; ?></td>
                    <td><?= $member['NoHP']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>