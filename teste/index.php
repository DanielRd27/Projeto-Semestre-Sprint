<?php
$apiKey = '72872d046ca2baa1e585a796cd99ccda'; // <-- Substitua com sua API key real
$resultados = [];

if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $query = urlencode($_GET['q']);
    $url = "https://api.themoviedb.org/3/search/movie?api_key=$apiKey&language=pt-BR&query=$query";

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (isset($data['results'])) {
        foreach ($data['results'] as $filme) {
            $resultados[] = [
            'id' => $filme['id'],
            'title' => $filme['title'],
            'overview' => $filme['overview']
        ];
        }

        // Salva o JSON em arquivo local (opcional)
        $nomeArquivo = 'resultado_' . preg_replace('/\W+/', '_', $_GET['q']) . '.json';
        file_put_contents($nomeArquivo, json_encode($resultados, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Buscar Filme - TMDB</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    input { padding: 8px; width: 300px; }
    button { padding: 8px 16px; }
    .filme { margin-top: 20px; padding: 10px; border-bottom: 1px solid #ccc; }
  </style>
</head>
<body>

<h2>Buscar Filme (TMDB)</h2>

<form method="get" action="">
  <input type="text" name="q" placeholder="Digite o nome do filme..." required value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
  <button type="submit">Buscar</button>
</form>

<?php if (!empty($resultados)): ?>
  <h3>Resultados:</h3>
  <?php foreach ($resultados as $filme): ?>
    <div class="filme">
      <strong><?= htmlspecialchars($filme['title']) ?></strong><br>
      ID: <?= $filme['id'] ?><br>
      Data de lançamento: <?= $filme['release_date'] ?? 'Desconhecida' ?><br>
      <?= htmlspecialchars($filme['overview']) ?>
    </div>
  <?php endforeach; ?>

  <hr>
  <h4>JSON gerado:</h4>
  <pre><?= json_encode($resultados, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?></pre>
<?php elseif (isset($_GET['q'])): ?>
  <p><strong>Nenhum resultado encontrado.</strong></p>
<?php endif; ?>

</body>
</html>
