<?php

$config = new PhpCsFixer\Config();

return $config->setRules([
    // PSR-12 standardına uygunluk
    '@PSR12' => true,

    // Dizi yazımı ve virgül kuralları
    'array_syntax' => ['syntax' => 'short'], // Kısa array syntax'ı
    'trailing_comma_in_multiline' => ['elements' => ['arrays']], // Çok satırlı arraylerde son virgül zorunlu

    // Kod temizliği ve okunabilirlik
    'no_unused_imports' => true, // Kullanılmayan "use" ifadelerini kaldır
    'single_quote' => true, // Stringlerde çift tırnak yerine tek tırnak
    'no_trailing_whitespace' => true, // Satır sonundaki gereksiz boşlukları kaldır
    'no_whitespace_in_blank_line' => true, // Boş satırlardaki boşlukları kaldır
    'trim_array_spaces' => true, // Array içindeki gereksiz boşlukları kaldır
    'no_empty_statement' => true, // Boş blokları engelle
    'blank_line_after_namespace' => true, // Namespace'den sonra bir boş satır ekle
    'blank_line_after_opening_tag' => true, // PHP açılış tag'inden sonra boşluk bırak
    'blank_line_before_statement' => ['statements' => ['return']], // "return" ifadelerinden önce boşluk bırak

    // İndentasyon ve satır sonu
    'indentation_type' => true, // İndentasyon space olacak
    'line_ending' => true, // Satır sonu LF olacak

    // PHP 8.2 ve üzeri modern syntax desteği
    //'modernize_strpos' => true, // strpos() yerine str_contains() kullanımı
    'nullable_type_declaration_for_default_null_value' => true, // Varsayılan değeri null olan tiplerde nullable yap
    //'declare_strict_types' => true, // Tüm dosyalar için "declare(strict_types=1);" ekle
    'class_attributes_separation' => ['elements' => ['method' => 'one', 'property' => 'one']], // Sınıf elemanları arasında boşluk bırak

    // PHP 8 özelliklerini etkin kullan
    //'static_lambda' => true, // Statik lambdaları kullan
    //'use_arrow_functions' => true, // Uygun yerlerde arrow function kullan
    'no_useless_nullsafe_operator' => true, // Gereksiz null-safe operatörlerini kaldır
    'self_static_accessor' => true, // Static erişim için $this yerine self kullan
    //'ordered_interfaces' => true, // Interface'leri alfabetik sırayla düzenle

    // Yorumlar ve PHPDoc
    'phpdoc_align' => ['align' => 'vertical'], // PHPDoc yorumlarını hizala
    'phpdoc_order' => true, // PHPDoc açıklamalarını sıralı hale getir
    'phpdoc_trim' => true, // PHPDoc içinde gereksiz boşlukları kaldır
    'phpdoc_types_order' => ['null_adjustment' => 'always_last', 'sort_algorithm' => 'none'], // Tip açıklamalarında null sona gelsin
    'phpdoc_separation' => true, // PHPDoc bölümler arasında boşluk bırak

    // Modern dönüşümler
    //'modernize_types_casting' => true, // Eski tip dönüşümleri modernleştir (örneğin, `(int)` yerine `(integer)`)
    'ternary_to_null_coalescing' => true, // Ternary operatör yerine null coalescing operatörü
    'short_scalar_cast' => true, // Kısa tip dönüşümlerini kullan

    // Sıkı kontroller
    //'strict_comparison' => true, // "===" ve "!==" zorunlu
    //'strict_param' => true, // Sıkı tip kontrolü

    // Bonus: Okunabilirliği artıran detaylar
    'single_line_throw' => false, // throw ifadelerini tek satırda değilse ayır
    'new_with_braces' => true, // New anahtar kelimesiyle parantez kullanımını zorunlu yap
    'no_superfluous_phpdoc_tags' => true, // Gereksiz PHPDoc etiketlerini kaldır
]);
