<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Statickidz\GoogleTranslate;

class TranslationService
{
    /**
     * Desteklenen diller
     */
    public static function getSupportedLanguages()
    {
        return [
            'tr' => 'Türkçe',
            'en' => 'English',
            'de' => 'Deutsch',
            'fr' => 'Français',
            'es' => 'Español',
            'it' => 'Italiano',
            'ru' => 'Русский',
            'ar' => 'العربية',
            'zh' => '中文',
            'ja' => '日本語',
            'ko' => '한국어',
            'pt' => 'Português',
            'nl' => 'Nederlands',
            'pl' => 'Polski',
            'sv' => 'Svenska',
            'da' => 'Dansk',
            'no' => 'Norsk',
            'fi' => 'Suomi',
            'el' => 'Ελληνικά',
            'he' => 'עברית',
            'hi' => 'हिन्दी',
            'uk' => 'Українська',
            'cs' => 'Čeština',
            'hu' => 'Magyar',
            'ro' => 'Română',
            'bg' => 'Български',
            'hr' => 'Hrvatski',
            'sk' => 'Slovenčina',
            'sl' => 'Slovenščina',
            'sr' => 'Српски',
            'mk' => 'Македонски',
            'sq' => 'Shqip',
            'et' => 'Eesti',
            'lv' => 'Latviešu',
            'lt' => 'Lietuvių',
        ];
    }

    /**
     * Metni çevirir
     * 
     * @param string $text Çevrilecek metin
     * @param string $targetLanguage Hedef dil (örn: 'en', 'tr')
     * @param string|null $sourceLanguage Kaynak dil (otomatik tespit edilir)
     * @return string Çevrilmiş metin
     */
    public static function translate($text, $targetLanguage, $sourceLanguage = null)
    {
        if (empty($text)) {
            return $text;
        }

        // Aynı dilse çevirme
        if ($sourceLanguage && $sourceLanguage === $targetLanguage) {
            return $text;
        }

        try {
            // statickidz/php-google-translate-free paketini kullan
            // Paket statik translate metodu kullanıyor: translate($source, $target, $text)
            $sourceLang = $sourceLanguage ?? 'auto';
            
            // Çeviriyi yap
            $translated = GoogleTranslate::translate($sourceLang, $targetLanguage, $text);
            
            return $translated ?: $text;
        } catch (\Exception $e) {
            Log::error('Translation error: ' . $e->getMessage());
            // Hata durumunda orijinal metni döndür
            return $text;
        }
    }

    /**
     * Dil kodunu tespit eder
     * 
     * @param string $text Tespit edilecek metin
     * @return string Dil kodu (örn: 'tr', 'en')
     */
    public static function detectLanguage($text)
    {
        if (empty($text)) {
            return 'tr';
        }

        try {
            // statickidz/php-google-translate-free paketi doğrudan dil tespiti yapmıyor
            // Basit bir heuristic kullanarak dil tespiti yapalım
            // Türkçe karakter kontrolü
            if (preg_match('/[çğıöşüÇĞIÖŞÜ]/', $text)) {
                return 'tr';
            }
            
            // İngilizce karakter kontrolü (basit)
            if (preg_match('/\b(the|and|or|is|are|was|were|to|of|a|an|in|on|at|for|with|by)\b/i', $text)) {
                return 'en';
            }
            
            // Varsayılan olarak Türkçe döndür
            return 'tr';
        } catch (\Exception $e) {
            Log::error('Language detection error: ' . $e->getMessage());
            // Varsayılan olarak Türkçe döndür
            return 'tr';
        }
    }
}

