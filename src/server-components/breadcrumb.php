<?php
/**
 * Breadcrumb JSON-LD Server Component
 *
 * @var Accela\Accela $accela
 * @var array $props
 * @var string $content
 *
 * Content format (JSON):
 *   {
 *     "/": "home",
 *     "/blog/": "blog",
 *     "@permalink": "@title"
 *   }
 *
 * - @key: props から URL を取得
 * - @value: props から label を取得
 * - @@value: リテラル @ から始まる文字列
 */

$map = json_decode(trim($content), true);

if (!$map || !is_array($map)) {
  return;
}

$baseUrl = $accela->url ?: "";
$items = [];

foreach ($map as $url => $label) {
  // URL: @ で始まれば props から取得
  if (str_starts_with($url, "@")) {
    $url = $props[substr($url, 1)] ?? "";
  }

  // Label: @@ で始まればエスケープ、@ で始まれば props から取得
  if (str_starts_with($label, "@@")) {
    $label = substr($label, 1);
  } elseif (str_starts_with($label, "@")) {
    $label = $props[substr($label, 1)] ?? "";
  }

  // 相対URLを絶対URLに変換
  if ($url && !preg_match('@^https?://@', $url)) {
    $url = rtrim($baseUrl, "/") . "/" . ltrim($url, "/");
  }

  $items[] = ["label" => $label, "url" => $url];
}

if (empty($items)) {
  return;
}

$itemListElement = array_map(function($item, $i) {
  $entry = [
    "@type" => "ListItem",
    "position" => $i + 1,
    "name" => $item["label"],
  ];
  if ($item["url"]) {
    $entry["item"] = $item["url"];
  }
  return $entry;
}, $items, array_keys($items));

$jsonLd = [
  "@context" => "https://schema.org",
  "@type" => "BreadcrumbList",
  "itemListElement" => $itemListElement,
];
?>
<script type="application/ld+json">
<?php echo json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
</script>
