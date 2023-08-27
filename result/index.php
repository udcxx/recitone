<?php
    require_once '../vars.php';

    $CURLERR = NULL;

    if (isset($_SERVER['HTTP_REFERER'])) {
        $referer = $_SERVER['HTTP_REFERER'];
    } else {
        $referer = "unknown";
    }

    $data = array(
        'prompt' => $_POST['prompt'],
        'ip_address' => $_SERVER['REMOTE_ADDR'], 
        'referer' => $referer
    );

    $data_json = json_encode($data);

    $context = array(
        'http' => array(
            'method'  => 'POST',
            'header'  => implode("\r\n", array('Content-Type: application/json',)),
            'content' => $data_json
        )
    );
    $responce = file_get_contents($url, false, stream_context_create($context));
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>レシピ｜RECITONE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=BIZ+UDPGothic:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <main>
        <h1>RECITONE by udcxx.</h1>

        <p class="description">レシピが生成されました。</p>

        <div class="bord">
            <div class="app success--wrap">
                <dl><dt>フィールド名</dt><dt>フィールドタイプ</dt><dt>選択肢</dt></dl>

            </div>
            <p class="bord--notice">※ 一部のフィールドは、複数のフィールドタイプが提案されています。「▼」から確認してください。</p>
        </div>

        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1301045842322864" crossorigin="anonymous"></script>
        <!-- recitone -->
        <ins class="adsbygoogle"
            style="display:block"
            data-ad-client="ca-pub-1301045842322864"
            data-ad-slot="5658273013"
            data-ad-format="auto"
            data-full-width-responsive="true"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>

        <footer>&copy; <a href="https://udcxx.me/" target="_blank">udcxx.me</a></footer>
    </main>



    <script>
        const checkJson = (data) => {
            try {
                JSON.parse(data);
            } catch (error) {
                if (typeof data === "object") {
                    if (data.fields[0].name === '') {
                        return false;
                    }
                    return true;
                } else {
                    return false;
                }
            }
            return true;
        }

        const response = <?php echo $responce; ?>

        if (checkJson(response)) {
            const createNameEl = (name) => {
                // フィールド名
                let fieldNamedd = document.createElement('dd');
                let fieldNameinput = document.createElement('input');
                fieldNameinput.type = "text";
                fieldNameinput.value = name;

                fieldNamedd.appendChild(fieldNameinput);

                return fieldNamedd;
            }

            const createOption = (option) => {
                let typeOption = document.createElement('option');
                typeOption.value = option;
                typeOption.innerHTML = option;

                return typeOption;
            }

            const createItems = (...items) => {
                let ul = document.createElement('ul');
                items[0].forEach((item) => {
                    let li = document.createElement('li');
                    li.innerHTML = item;

                    ul.appendChild(li);
                });

                return ul;
            }

            const createFieldElChild = (field) => {
                let ddName = createNameEl(field.name);
                let selectItem = '';

                let ddType = document.createElement('dd');
                let fieldTypeSelect = document.createElement('select');

                if (field.type === "text") {
                    fieldTypeSelect.appendChild(createOption('文字列（1行）'));

                } else if (field.type === "textarea") {
                    fieldTypeSelect.appendChild(createOption('文字列（複数行）'));
                    fieldTypeSelect.appendChild(createOption('リッチエディター'));

                } else if (field.type === "number") {
                    fieldTypeSelect.appendChild(createOption('数値'));

                } else if (field.type === "checkbox") {
                    fieldTypeSelect.appendChild(createOption('チェックボックス'));
                    fieldTypeSelect.appendChild(createOption('複数選択'));

                    selectItem = createItems(field.items);

                } else if (field.type === "radio") {
                    fieldTypeSelect.appendChild(createOption('ラジオボタン'));
                    fieldTypeSelect.appendChild(createOption('ドロップダウン'));

                    selectItem = createItems(field.items);

                } else if (field.type === "datetime") {
                    fieldTypeSelect.appendChild(createOption('日時'));

                } else if (field.type === "date") {
                    fieldTypeSelect.appendChild(createOption('日付'));

                } else if (field.type === "time") {
                    fieldTypeSelect.appendChild(createOption('時刻'));
                }

                ddType.appendChild(fieldTypeSelect);

                let dl = document.createElement('dl');
                dl.appendChild(ddName);
                dl.appendChild(ddType);

                if (selectItem) {
                    dl.appendChild(selectItem);    
                }

                return dl;
            }

            response.fields.forEach((field) => {
                document.querySelector('.app').appendChild(createFieldElChild(field));

            });
        } else {
            document.querySelector('.description').innerHTML = `レシピの生成に失敗しました。<br>お手数ですが、 <a href="../">こちら</a> から再度お試しください。`;
            document.querySelector('.bord').style.display = 'none';
        }
    </script>
</body>
</html>