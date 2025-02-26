document.addEventListener('DOMContentLoaded', function() {
    let rowCount = document.querySelectorAll('#img-table tr').length;

    document.getElementById('add-row').addEventListener('click', function() {
        const tableBody = document.getElementById('img-table');

        const newRow = document.createElement('tr');
        newRow.innerHTML = `
        <td class="d-flex align-items-center justify-content-around">
            <div class="mt-2" style="width: 100%;">
                <div class="border rounded">
                    <div class="d-flex p-2">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm bg-light rounded">
                                <img id="preview_${rowCount}" src="" style="width: 45px; height: 45px; object-fit: cover;">
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="pt-1" style="width: 73%;">
                                <input type="file" id="img_thumbnail_${rowCount}" name="img_thumbnail[new_${rowCount}]"
                                       class="form-control" onchange="previewImg(this, ${rowCount})">
                            </div>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <button class="btn btn-sm btn-danger" type="button" onclick="removeRow(this)">Xóa</button>
                        </div>
                    </div>
                </div>
            </div>
        </td>`;
        tableBody.appendChild(newRow);
        rowCount++;
    });

    window.previewImg = function(input, index) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(`preview_${index}`).src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    window.removeRow = function(item) {
        const row = item.closest('tr');
        row.remove();
    };
});