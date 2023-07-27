
        function confirmLogout() {
            // アラートを表示してログアウト確認
            if (confirm('本当にログアウトしますか？')) {
                // ログアウト処理をPOSTで送信
                const form = document.createElement('form');
                form.method = 'post';
                form.action = 'logout.php';
                document.body.appendChild(form);
                form.submit();
            } else {
                // キャンセルボタンを押した場合はフォームの送信を中止
                return false;
            }
        }
