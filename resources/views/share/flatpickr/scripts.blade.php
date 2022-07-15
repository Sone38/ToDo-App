<!-- flatpiickrスクリプト -->
<script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
<!-- 日本語化のための追加スクリプト -->
<script src="https://npmcdn.com/flatpickr/dist/l10n/ja.js"></script>
<script>
    flatpickr(document.getElementById('due_date'), {
        locale: 'ja',
        dateformat: "Y/m/d",
        minDate: new Date()
    });
</script>
