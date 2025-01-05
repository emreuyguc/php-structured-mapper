# Euu Php Structured Mapper

![PHP Version](https://img.shields.io/badge/php-%3E%3D8.0-blue)
![License](https://img.shields.io/badge/license-MIT-green)
![Author](https://img.shields.io/badge/author-Emre%20Utku%20Uygu%C3%A7-orange)

Euu Structured Mapper, PHP için basit ve deneysel bir veri dönüştürme kütüphanesidir.

Bu proje, attribute kullanarak dönüşüm tanımlamaları yapmayı ve veri türü dönüşümleri gerçekleştirmeyi kolaylaştırmayı hedefler. Aynı zamanda kullanımı son derece basit bir yapı sunar.

## Özellikler

- **Attribute ile Mapping Tanımlama:** Dönüşüm kurallarını attribute kullanarak tanımlama imkanı.
- **mapFrom ve mapTo ile Dönüşüm Tanımlama:** Dönüşüm kurallarını kaynak veya hedef sınıfın herhangi birinde belirleyebilme.
- **Direkt Property Üzerinde Tanımlama:** Özellik bazlı dönüşüm tanımlamaları yapabilme.
- **Context Aktarımı ve Kullanımı:** Mapleme sırasında bağlam bilgilerini aktarabilme.
- **Value Transformerlar:** Tür, tip ve veri dönüşümleri için value transformer yapısı.
- **Önemli Transformerlar:** Doctrine Entity, Enum, Array item gibi yaygın dönüşümler için paketle birlikte gelen value transformerlar.
- **Structure Read ve Storage Yapısı:** Genişletilebilir bir yapı sağlayan structure reader ile dönüşüm tanımlamalarını farklı kaynaklardan okuyabilme.
- **Custom Mapper Tanımlamaları:** Özel yazılmış dönüşüm sınıflarını tanımlayabilme.(bknz: MapperRegistry ve MapperInterface)
- **Array Elemanı Dönüşümleri:** Array elemanlarını dönüştürme ve her elemanı value transformer ile işleyebilme imkanı.(bknz: ValueTransformer/ArrayItemTransform/ArrayItemTransformer.php)
- **Sub Object Dönüşüm Tanımlayabilme:** Child objelere dönüşüm tanımlamaları yapabilme. (bknz: ValueTransformer/ObjectTransform/WithMapper.php)

## Kurulum

Projenize Composer kullanarak Euu Structured Mapper'ı ekleyin:

```bash
composer require emreuyguc/structured-mapper
```

## Kullanım

Detaylı kullanım örnekleri için `example/ExampleFactory.php` dosyasını inceleyebilirsiniz. Kendi kullanım senaryolarınıza göre mapper'ı `init` edebilirsiniz.

## Örnekler

- `example/MapFromExample.php`
- `example/MapToExample.php`
- `example/Dto/`
- `example/Entity/`

## Kısıtlamalar ve Düşünceler

- **Object Constructor Sorunu:** Constructor içeren objeler dönüşüm esnasında `init` edilemiyor. Bu alan geliştirilmeye açıktır. (Öncelik: Yüksek)
- **Ters Dönüşüm İmkanı:** `a -> b` dönüşümleri tanımlanmışsa, Aslında bunun ters dönüşümüde limitler dahilinde mümkündür (`b -> a`) fakat bu özellik şuan desteklenmemektedir.  (Öncelik: Orta)
- **Tip Dönüşüm Mekanizması:** Genel tip dönüşümleri için bir mekanizma bulunmamaktadır. Kullanıcı bunu manuel gerçekleştirmelidir veya basit dönüşümler için `type_enforcement` parametresi `false` yapılabilir.  (Öncelik: Düşük)
- **Property İsimlendirme:** Property isimleri mapping esnasında manuel tanımlanır.Bunun yerine Otomatik bir isim dönüştürme mekanizması eklenebilir.  (Öncelik: 0rta)
- **Cache Mekanizması:** Şu an için herhangi bir cache mekanizması mevcut değildir. Uygun bir cache yapısı eklenebilir.  (Öncelik: Yüksek)
- **Map kontrolü:** `canMap` gibi bir metod eklenerek kontrol yapılabilir.  (Öncelik: Yüksek)
- **Auto Sub Type Mapping:** Child objelerin tiplerine göre arama yaparak dönüşüm yapabilme özelliği eklenebilir.  (Öncelik: Orta)
- **Custom Mapper Tanımında Dönüşüm Tipi kontrolü:** Custom mapper tanımlamalarında geçerli Source ve Destination tanımlaması yapılabilir. Şuanda bu kontrol yapılmamaktadır.  (Öncelik: Yüksek)
- **Entity Find işlevinde Expression kullanımı:** Entity dönüşümlerinde custom parametreler için expression lang kullanılabilir  (Öncelik: Düşük)
- **Testler:** Testler yazılacak.  (Öncelik: Yüksek)

## Lisans

Bu paket [MIT Lisansı](LICENSE.md) ile lisanslanmıştır.
