# Polirium Tabler Datatable

> Một datatable component mạnh mẽ cho Laravel Livewire với giao diện Tabler UI

## Giới thiệu

Polirium Tabler Datatable là một package nội bộ của Polirium, được xây dựng dựa trên Livewire PowerGrid. Package cung cấp các tính năngdatatable hiện đại, dễ tùy chỉnh cho các ứng dụng Laravel.

## Tính năng

- ✅ **Tabler UI Framework** - Giao diện đẹp mắt, tích hợp sẵn với Tabler
- ✅ **Phân trang** - Hỗ trợ phân trang tự động
- ✅ **Sắp xếp cột** - Sắp xếp dữ liệu theo cột
- ✅ **Bộ lọc & Tìm kiếm** - Lọc dữ liệu theo nhiều điều kiện khác nhau
- ✅ **Chỉnh sửa inline** - Click để edit trực tiếp trên bảng
- ✅ **Hàng loạt hành động** - Bulk actions với checkbox
- ✅ **Export dữ liệu** - Xuất ra XLSX/CSV
- ✅ **Responsive** - Hiển thị tốt trên mọi thiết bị
- ✅ **Đa ngôn ngữ** - Hỗ trợ tiếng Việt và nhiều ngôn ngữ khác

## Cài đặt

### Yêu cầu

- PHP 8.2+
- Laravel 10+
- Livewire 3.0+
- [Tabler UI](https://tabler.io)

### Cách sử dụng

Package này được cài đặt sẵn trong Polirium Platform. Không cần cài đặt thủ công.

### Cấu hình

```bash
php artisan vendor:publish --tag=polirium-datatable-config
```

File config sẽ được xuất tại `config/polirium-datatable.php`

## Hướng dẫn sử dụng cơ bản

### Tạo DataTable mới

```bash
php artisan make:datatable ProductTable
```

### Định nghĩa cột

```php
use PowerComponents\LivewirePowerGrid\Column;

public function columns(): array
{
    return [
        Column::add()
            ->title('ID')
            ->field('id')
            ->sortable(),

        Column::add()
            ->title('Tên')
            ->field('name')
            ->searchable()
            ->sortable(),
    ];
}
```

### DataSource

```php
public function datasource(): ?Builder
{
    return Product::query();
}
```

## Framework hỗ trợ

Package hiện tại hỗ trợ các framework sau:

| Framework | Trạng thái |
|-----------|------------|
| Tabler UI | ✅ Mặc định |
| Bootstrap 5 | ✅ Có sẵn |

## Tài liệu tham khảo

Vì đây là package nội bộ, vui lòng liên kết với team phát triển để được hỗ trợ.

## Phiên bản

Phiên bản hiện tại: **1.0.0**

## License

© 2025 Polirium. Package nội bộ, không được phân phối bên ngoài.
