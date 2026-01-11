# Polirium Tabler Datatable

> A powerful datatable component for Laravel Livewire with Tabler UI framework

## Giới thiệu | Introduction

**Tiếng Việt:** Polirium Tabler Datatable là một package nội bộ của Polirium, được xây dựng dựa trên Livewire PowerGrid. Package cung cấp các tính năng datatable hiện đại, dễ tùy chỉnh cho các ứng dụng Laravel.

**English:** Polirium Tabler Datatable is an internal package of Polirium, built based on Livewire PowerGrid. This package provides modern, customizable datatable features for Laravel applications.

---

## Tính năng | Features

- ✅ **Tabler UI Framework** - Beautiful interface integrated with Tabler
- ✅ **Pagination** - Automatic pagination support
- ✅ **Column Sorting** - Sort data by columns
- ✅ **Filters & Search** - Filter data with multiple conditions
- ✅ **Inline Editing** - Click to edit directly on the table
- ✅ **Bulk Actions** - Perform actions on multiple rows with checkboxes
- ✅ **Data Export** - Export to XLSX/CSV
- ✅ **Responsive** - Display well on all devices
- ✅ **Multi-language** - Support Vietnamese and many other languages

---

## Cài đặt | Installation

### Yêu cầu | Requirements

- PHP 8.2+
- Laravel 10+
- Livewire 3.0+
- [Tabler UI](https://tabler.io)

### Cách sử dụng | Usage

**Tiếng Việt:** Package này được cài đặt sẵn trong Polirium Platform. Không cần cài đặt thủ công.

**English:** This package is pre-installed in Polirium Platform. Manual installation is not required.

### Cấu hình | Configuration

```bash
php artisan vendor:publish --tag=polirium-datatable-config
```

**Tiếng Việt:** File config sẽ được xuất tại `config/polirium-datatable.php`

**English:** The config file will be published to `config/polirium-datatable.php`

---

## Hướng dẫn sử dụng | Usage Guide

### Tạo DataTable mới | Create a New DataTable

```bash
php artisan make:datatable ProductTable
```

### Định nghĩa cột | Define Columns

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
            ->title('Name')
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

---

## Framework hỗ trợ | Supported Frameworks

| Framework | Status |
|-----------|--------|
| Tabler UI | ✅ Default |
| Bootstrap 5 | ✅ Available |

---

## Tài liệu tham khảo | Documentation

**Tiếng Việt:** Vì đây là package nội bộ, vui lòng liên hệ với team phát triển để được hỗ trợ.

**English:** As this is an internal package, please contact the development team for support.

---

## Phiên bản | Version

**Tiếng Việt:** Phiên bản hiện tại: **1.0.0**

**English:** Current version: **1.0.0**

---

## License

© 2025 Polirium. Internal package, not for external distribution.
