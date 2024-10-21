function generateCode() {

    let categorySelect = document.querySelector('select[name="category_id"]');

        categorySelect.addEventListener("change", function() {
            let categoryId = categorySelect.value;

            // Cari objek yang memiliki category_id sesuai dengan yang dipilih
            let selectedCategory = {!! json_encode($category) !!}.find(function(category) {
                return category.category_id == categoryId;
            });

            // Jika kategori yang dipilih ditemukan, dapatkan category_name-nya
            if (selectedCategory) {
                let categoryName = selectedCategory.category_name;
                console.log("Selected category_name:", categoryName);

                // Gunakan categoryName sesuai kebutuhan di sini
            } else {
                console.error("Category not found for category_id:", categoryId);
            }
        });
    });

    let tower = document.getElementById('tower').value;
    let floor = document.getElementById('floor').value;
    let room = document.getElementById('room').value;

    let location = 'Gedung ' + tower + ', Ruangan ' + room + ' di Lantai ' + floor;
    document.getElementById('asset_position').value = location;

    let asset_name = document.getElementById('asset_name').value;
    let asset_type = document.getElementById('asset_type').value;
    
    // Mendapatkan nilai category_id yang dipilih
    let categoryId = document.querySelector('select[name="category_id"]').value;

    // Mendapatkan objek kategori yang sesuai dengan category_id yang dipilih
    let selectedCategory = {!! json_encode($category) !!}.find(function(category) {
        return category.category_id == categoryId;
    });

    // Mendapatkan nilai category_name dari objek kategori yang ditemukan
    let categoryName = selectedCategory ? selectedCategory.category_name : '';

    let initials = categoryName.split(' ').map(word => word.charAt(0).toUpperCase()).join(''); // Menggunakan categoryName

    let asset_date_value = document.getElementById('asset_date_of_entry').value;

    let name_prefix = asset_name.substring(0, 3).toUpperCase();

    let asset_date = new Date(asset_date_value);
    let year = asset_date.getFullYear().toString().slice(-2);
    let month = (asset_date.getMonth() + 1).toString().padStart(2, '0'); // Menambahkan padding ke bulan jika perlu

    let code = initials + '-' + name_prefix + '(' + room+tower+floor + ')';

    document.getElementById('asset_code').value = code;

    console.log("Generated asset code:", code); // Logging untuk memastikan kode aset dihasilkan dengan benar
}
}

