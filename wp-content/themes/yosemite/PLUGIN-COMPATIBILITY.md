# 🔧 Plugin Compatibility Guide

## 📋 Tổng Quan

Theme Yosemite đã được tối ưu để tương thích hoàn toàn với các plugin optimization phổ biến như **WP Rocket** và **Smush Pro**. File `plugin-compatibility.php` tự động phát hiện và điều chỉnh các optimizations để tránh conflicts.

## 🚀 WP Rocket Compatibility

### ✅ **Tự Động Phát Hiện**
Theme tự động phát hiện khi WP Rocket được kích hoạt và điều chỉnh các optimizations tương ứng.

### 🔄 **Các Điều Chỉnh Tự Động**

#### **1. Cache Headers**
- **Trước**: Theme thêm cache headers riêng
- **Sau**: Tự động disable khi WP Rocket active
- **Lý do**: WP Rocket đã xử lý caching tốt hơn

#### **2. Minification**
- **Trước**: Theme minify CSS/JS
- **Sau**: Tự động disable khi WP Rocket minification active
- **Lý do**: Tránh double minification

#### **3. Defer Attributes**
- **Trước**: Theme thêm defer cho scripts
- **Sau**: Tự động disable khi WP Rocket delay JS active
- **Lý do**: WP Rocket có delay JS tốt hơn

#### **4. Preload Resources**
- **Trước**: Theme preload fonts và images
- **Sau**: Tự động disable khi WP Rocket preload active
- **Lý do**: WP Rocket preload hiệu quả hơn

### 🎯 **WP Rocket Specific Optimizations**

```php
// Critical CSS inline khi WP Rocket critical CSS disabled
if (!get_option('rocket_critical_css')) {
    echo '<style id="mts-critical-css">';
    echo 'body{font-family:system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;line-height:1.6;color:#333;margin:0;padding:0}';
    echo '.container{max-width:1200px;margin:0 auto;padding:0 20px}';
    echo '.header{background:#fff;box-shadow:0 2px 4px rgba(0,0,0,0.1)}';
    echo '.main-content{min-height:400px;padding:20px 0}';
    echo '.footer{background:#f8f9fa;padding:20px 0;margin-top:40px}';
    echo '</style>';
}
```

## 🖼️ Smush Pro Compatibility

### ✅ **Tự Động Phát Hiện**
Theme tự động phát hiện khi Smush Pro được kích hoạt và điều chỉnh image optimizations.

### 🔄 **Các Điều Chỉnh Tự Động**

#### **1. WebP Generation**
- **Trước**: Theme tự generate WebP images
- **Sau**: Tự động disable khi Smush Pro WebP active
- **Lý do**: Smush Pro WebP tốt hơn và có fallback

#### **2. Lazy Loading**
- **Trước**: Theme lazy loading riêng
- **Sau**: Tự động disable khi Smush Pro lazy loading active
- **Lý do**: Smush Pro lazy loading có Intersection Observer

### 🎯 **Smush Pro Specific Optimizations**

```javascript
// WebP support detection
function supportsWebP() {
  var elem = document.createElement("canvas");
  return !!(elem.getContext && elem.getContext("2d"));
}
if (supportsWebP()) {
  document.documentElement.classList.add("webp");
} else {
  document.documentElement.classList.add("no-webp");
}

// Lazy loading fallback
if ("loading" in HTMLImageElement.prototype) {
  var images = document.querySelectorAll("img[data-src]");
  images.forEach(function(img) {
    img.src = img.dataset.src;
    img.removeAttribute("data-src");
  });
} else {
  var script = document.createElement("script");
  script.src = "https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js";
  document.head.appendChild(script);
}
```

## 🔧 Other Optimization Plugins

### **W3 Total Cache**
- ✅ Tự động disable theme cache headers
- ✅ Tự động disable theme minification
- ✅ Tương thích hoàn toàn

### **WP Super Cache**
- ✅ Tự động disable theme cache headers
- ✅ Tương thích hoàn toàn

### **Autoptimize**
- ✅ Tự động disable theme minification
- ✅ Tự động disable theme defer attributes
- ✅ Tương thích hoàn toàn

### **WP Fastest Cache**
- ✅ Tự động disable theme cache headers
- ✅ Tương thích hoàn toàn

### **LiteSpeed Cache**
- ✅ Tự động disable theme cache headers
- ✅ Tự động disable theme minification
- ✅ Tương thích hoàn toàn

## 📊 Compatibility Matrix

| Plugin | Cache Headers | Minification | Defer Attributes | Preload | WebP | Lazy Loading |
|--------|---------------|--------------|------------------|---------|------|--------------|
| **WP Rocket** | ❌ Disabled | ❌ Disabled | ❌ Disabled | ❌ Disabled | ✅ Theme | ✅ Theme |
| **Smush Pro** | ✅ Theme | ✅ Theme | ✅ Theme | ✅ Theme | ❌ Disabled | ❌ Disabled |
| **W3 Total Cache** | ❌ Disabled | ❌ Disabled | ✅ Theme | ✅ Theme | ✅ Theme | ✅ Theme |
| **WP Super Cache** | ❌ Disabled | ✅ Theme | ✅ Theme | ✅ Theme | ✅ Theme | ✅ Theme |
| **Autoptimize** | ✅ Theme | ❌ Disabled | ❌ Disabled | ✅ Theme | ✅ Theme | ✅ Theme |
| **WP Fastest Cache** | ❌ Disabled | ✅ Theme | ✅ Theme | ✅ Theme | ✅ Theme | ✅ Theme |
| **LiteSpeed Cache** | ❌ Disabled | ❌ Disabled | ✅ Theme | ✅ Theme | ✅ Theme | ✅ Theme |

**Legend**: ✅ = Active, ❌ = Disabled

## 🎛️ Customizer Options

### **Plugin Compatibility Section**
Theme thêm section mới trong Customizer để quản lý compatibility:

1. **WP Rocket Compatibility**: Tự động điều chỉnh khi WP Rocket active
2. **Smush Pro Compatibility**: Tự động điều chỉnh khi Smush Pro active
3. **Other Plugins Compatibility**: Tự động điều chỉnh cho các plugin khác

### **Cách Truy Cập**
1. Vào **Appearance > Customize**
2. Tìm section **Plugin Compatibility**
3. Bật/tắt các tùy chọn tương ứng

## 🔍 Debug Information

### **Cách Kiểm Tra Compatibility**
Thêm `?debug_plugins=1` vào URL để xem thông tin debug:

```
https://yourdomain.com/?debug_plugins=1
```

### **Thông Tin Hiển Thị**
- WP Rocket: Active/Inactive
- Smush Pro: Active/Inactive
- W3 Total Cache: Active/Inactive
- WP Super Cache: Active/Inactive
- Autoptimize: Active/Inactive
- WP Fastest Cache: Active/Inactive
- LiteSpeed Cache: Active/Inactive

## 🚨 Common Issues & Solutions

### **Issue 1: Double Minification**
**Triệu chứng**: CSS/JS bị minify 2 lần, gây lỗi
**Nguyên nhân**: Cả theme và plugin đều minify
**Giải pháp**: Theme tự động disable minification khi plugin active

### **Issue 2: Cache Conflicts**
**Triệu chứng**: Cache không hoạt động đúng
**Nguyên nhân**: Cả theme và plugin đều set cache headers
**Giải pháp**: Theme tự động disable cache headers khi plugin active

### **Issue 3: WebP Conflicts**
**Triệu chứng**: WebP images không hiển thị
**Nguyên nhân**: Cả theme và Smush Pro đều generate WebP
**Giải pháp**: Theme tự động disable WebP generation khi Smush Pro active

### **Issue 4: Lazy Loading Conflicts**
**Triệu chứng**: Images không lazy load đúng cách
**Nguyên nhân**: Cả theme và plugin đều lazy load
**Giải pháp**: Theme tự động disable lazy loading khi plugin active

## 📈 Performance Impact

### **With WP Rocket**
- **Cache**: WP Rocket handles caching (better performance)
- **Minification**: WP Rocket handles minification (better compression)
- **Preload**: WP Rocket handles preloading (better resource hints)
- **Delay JS**: WP Rocket handles delay JS (better Core Web Vitals)

### **With Smush Pro**
- **WebP**: Smush Pro handles WebP (better compression + fallback)
- **Lazy Loading**: Smush Pro handles lazy loading (better Intersection Observer)
- **Image Optimization**: Smush Pro handles image optimization (better quality)

### **Overall Impact**
- **No Conflicts**: Zero conflicts between theme and plugins
- **Better Performance**: Plugin optimizations take precedence
- **Automatic Detection**: No manual configuration needed
- **Future Proof**: Compatible with plugin updates

## 🔧 Manual Configuration

### **Disable Auto-Detection**
Nếu muốn disable auto-detection:

```php
// Trong functions.php
remove_action('init', 'mts_init_plugin_compatibility');
```

### **Force Theme Optimizations**
Nếu muốn force theme optimizations:

```php
// Trong functions.php
add_filter('mts_check_wp_rocket_compatibility', '__return_false');
add_filter('mts_check_smush_pro_compatibility', '__return_false');
```

### **Custom Compatibility Rules**
Nếu muốn thêm custom rules:

```php
// Trong functions.php
add_action('mts_handle_wp_rocket_conflicts', 'my_custom_wp_rocket_rules');
function my_custom_wp_rocket_rules() {
    // Custom WP Rocket rules
}
```

## 🎯 Best Practices

### **1. Plugin Priority**
- **WP Rocket**: Highest priority (caching, minification, preload)
- **Smush Pro**: High priority (images, WebP, lazy loading)
- **Theme**: Fallback optimizations

### **2. Configuration Order**
1. Install và activate plugins
2. Configure plugin settings
3. Theme tự động detect và adjust
4. Test performance

### **3. Monitoring**
- Regular performance testing
- Check for conflicts
- Update plugins regularly
- Monitor Core Web Vitals

## 📞 Support

### **Troubleshooting Steps**
1. Check plugin compatibility status
2. Verify plugin settings
3. Test with debug mode
4. Check browser console for errors
5. Contact support if needed

### **Debug Mode**
Enable debug mode để xem chi tiết:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

---

**Lưu ý**: Theme Yosemite được thiết kế để hoạt động tốt nhất với WP Rocket và Smush Pro. Tất cả compatibility được xử lý tự động, không cần cấu hình thêm.
