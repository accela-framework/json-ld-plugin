# Accela JSON-LD Plugin

JSON-LD構造化データを出力するAccelaプラグイン。

## インストール

```bash
composer require accela-framework/json-ld-plugin
```

## 設定

`index.php` でプラグインを有効化：

```php
$accela = new Accela([
  "appDir" => __DIR__ . "/app",
  "url" => "https://example.com",
  "plugins" => [
    "json-ld" => []
  ]
]);
```

## コンポーネント

### breadcrumb

パンくずリストの構造化データを出力。

```html
<head>
  <title data-bind-text="title"></title>
  
  <accela-server-component use="json-ld:breadcrumb">
  {
    "/": "ホーム",
    "/blog/": "ブログ",
    "@permalink": "@title"
  }
  </accela-server-component>
</head>
```

#### Content フォーマット

JSON形式で `"URL": "ラベル"` を記述：

```json
{
  "/": "ホーム",
  "/blog/": "ブログ",
  "@category_url": "@category",
  "@permalink": "@title"
}
```

#### 記法

| 記法 | 説明 |
|------|------|
| `"/path/"` | 静的URL |
| `"ラベル"` | 静的ラベル |
| `"@prop"` | Page Props から取得 |
| `"@@text"` | `@` で始まるリテラル文字列 |

#### 出力例

```html
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"ホーム","item":"https://example.com/"},{"@type":"ListItem","position":2,"name":"ブログ","item":"https://example.com/blog/"},{"@type":"ListItem","position":3,"name":"記事タイトル","item":"https://example.com/blog/my-post/"}]}
</script>
```

## ライセンス

MIT
