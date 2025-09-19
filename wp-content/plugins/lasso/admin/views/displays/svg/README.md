# SVG Icons Directory

Thư mục này chứa các file SVG cho stores và platforms.

## Cấu trúc thư mục:

```
svg/
├── stores/           # SVG icons cho các cửa hàng game
│   ├── steam.svg
│   ├── epic-games.svg
│   ├── gog.svg
│   ├── apple-appstore.svg
│   ├── google-play.svg
│   ├── itch.svg
│   ├── nintendo.svg
│   ├── playstation-store.svg
│   └── xbox-store.svg
└── platforms/        # SVG icons cho các platform
    ├── pc.svg
    ├── xbox.svg
    ├── playstation.svg
    ├── nintendo.svg
    └── mobile.svg
```

## Cách sử dụng:

### 1. Thay thế SVG cho Stores:
- Mở file tương ứng trong thư mục `stores/`
- Paste SVG code mới vào file (thay thế nội dung hiện tại)
- Lưu file

### 2. Thay thế SVG cho Platforms:
- Mở file tương ứng trong thư mục `platforms/`
- Paste SVG code mới vào file (thay thế nội dung hiện tại)
- Lưu file

### 3. Thêm Store/Platform mới:
- Tạo file `.svg` mới trong thư mục tương ứng
- Paste SVG code vào file
- Cập nhật mapping trong `layout-6-box.php` nếu cần

## Lưu ý:
- SVG code sẽ được load tự động từ file
- Không cần chỉnh sửa code PHP
- File SVG sẽ được cache để tối ưu performance
- Fallback SVG sẽ được sử dụng nếu file không tồn tại

## Ví dụ:
```php
// Trong layout-6-box.php
echo getStoreSVG('steam');        // Load từ stores/steam.svg
echo getPlatformSVG('xbox');      // Load từ platforms/xbox.svg
```
