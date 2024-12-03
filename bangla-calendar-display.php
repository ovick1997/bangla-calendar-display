<?php
/**
 * Plugin Name: Bangla Calendar Display
 * Plugin URI: https://your-website.com/bangla-calendar-display
 * Description: A plugin to display the Bengali date and time on your WordPress site. Short Code is [bangla_calendar] .
 * Version: 1.0.1
 * Author: iamovk
 * Author URI: https://shorovabedin.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 5.0
 * Tested up to: 6.7
 * Requires PHP: 5.6
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Trait for adding Bangla suffixes.
 */
trait BCD_HasBanglaSuffix {
    public function add_bangla_suffix( $number ) {
        // Add the "ই" suffix for Bangla numbers (e.g., ১৮ becomes ১৮ই).
        return $number . 'ই';
    }
}

/**
 * Main Bangla Calendar Display class.
 */
class BCD_BanglaCalendar {
    use BCD_HasBanglaSuffix;

    /**
     * Bengali months.
     */
    private $bangla_months = [
        'বৈশাখ', 'জ্যৈষ্ঠ', 'আষাঢ়', 'শ্রাবণ', 'ভাদ্র', 'আশ্বিন',
        'কার্তিক', 'অগ্রহায়ণ', 'পৌষ', 'মাঘ', 'ফাল্গুন', 'চৈত্র'
    ];

    /**
     * Bengali weekdays.
     */
    private $bangla_weekdays = [
        'রবিবার', 'সোমবার', 'মঙ্গলবার', 'বুধবার', 'বৃহস্পতিবার', 'শুক্রবার', 'শনিবার'
    ];

    /**
 * Get the formatted Bangla date and time.
 *
 * @return string
 */
public function get_bangla_date() {
    // Set timezone to Dhaka
    $timezone = new DateTimeZone('Asia/Dhaka');
    $datetime = new DateTime('now', $timezone);

    // Convert time to Bangla.
    $time = $this->convert_to_bangla_time( $datetime->format('h:i A') );

    // Get day of the week in Bangla.
    $weekday = $this->bangla_weekdays[ $datetime->format('w') ];

    // Convert Gregorian date to Bengali calendar.
    $bangla_date = $this->convert_to_bangla_calendar( $datetime->format('Y-m-d') );

    // Format full Bangla date string.
    return "{$time}, {$weekday}, {$bangla_date}";
}


    /**
     * Convert Gregorian date to Bangla calendar format.
     *
     * @param string $date Gregorian date.
     * @return string Bangla formatted date.
     */
    public function convert_to_bangla_calendar( $date ) {
        // Placeholder logic for conversion.
        // Replace this with actual date conversion algorithm.
        $gregorian_parts = explode( '-', $date );
        $year = $gregorian_parts[0];
        $month = $gregorian_parts[1];
        $day = $gregorian_parts[2];

        // Convert to Bangla numerals and format.
        $bangla_day = $this->add_bangla_suffix( $this->convert_to_bangla_number( $day ) );
        $bangla_month = $this->bangla_months[ (int) $month - 1 ];
        $bangla_year = $this->convert_to_bangla_number( $year - 593 ); // Adjust to Bengali year.

        return "{$bangla_day} {$bangla_month}, {$bangla_year} বঙ্গাব্দ";
    }

    /**
     * Convert time to Bangla format with AM/PM.
     *
     * @param string $time Time in 'h:i A' format.
     * @return string Bangla formatted time.
     */
    public function convert_to_bangla_time( $time ) {
        $time_parts = explode( ' ', $time );
        $time_numbers = $time_parts[0];
        $meridian = $time_parts[1];

        $bangla_time = $this->convert_to_bangla_number( $time_numbers );
        $bangla_meridian = ( $meridian === 'AM' ) ? 'পূর্বাহ্ণ' : 'অপরাহ্ণ';

        return "{$bangla_meridian} {$bangla_time}";
    }

    /**
     * Convert English numbers to Bangla numbers.
     *
     * @param string $number English number.
     * @return string Bangla number.
     */
    public function convert_to_bangla_number( $number ) {
        $english_numbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $bangla_numbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        return str_replace( $english_numbers, $bangla_numbers, $number );
    }
}

/**
 * Shortcode handler for displaying Bangla date.
 *
 * @return string
 */
function bcd_bangla_calendar_shortcode() {
    $bangla_calendar = new BCD_BanglaCalendar();
    return $bangla_calendar->get_bangla_date();
}
add_shortcode( 'bangla_calendar', 'bcd_bangla_calendar_shortcode' );

