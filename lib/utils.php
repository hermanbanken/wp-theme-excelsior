<?php
/**
 * Theme wrapper
 *
 * @link http://scribu.net/wordpress/theme-wrappers.html
 */
function roots_template_path() {
  return Roots_Wrapping::$main_template;
}

function roots_sidebar_path() {
  return Roots_Wrapping::sidebar();
}

class Roots_Wrapping {
  // Stores the full path to the main template file
  static $main_template;

  // Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
  static $base;

  static function wrap($template) {
    self::$main_template = $template;

    self::$base = substr(basename(self::$main_template), 0, -4);

    if (self::$base === 'index') {
      self::$base = false;
    }

    $templates = array('base.php');

    if (self::$base) {
      array_unshift($templates, sprintf('base-%s.php', self::$base));
    }

    return locate_template($templates);
  }

  static function sidebar() {
    $templates = array('templates/sidebar.php');

    if (self::$base) {
      array_unshift($templates, sprintf('templates/sidebar-%s.php', self::$base));
    }

    return locate_template($templates);
  }
}
add_filter('template_include', array('Roots_Wrapping', 'wrap'), 99);

/**
 * Page titles
 */
function roots_title() {
  if (is_home()) {
    if (get_option('page_for_posts', true)) {
      echo get_the_title(get_option('page_for_posts', true));
    } else {
      _e('Latest Posts', 'roots');
    }
  } elseif (is_archive()) {
    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    if ($term) {
      echo $term->name;
    } elseif (is_post_type_archive()) {
      echo get_queried_object()->labels->name;
    } elseif (is_day()) {
      printf(__('Daily Archives: %s', 'roots'), get_the_date());
    } elseif (is_month()) {
      printf(__('Monthly Archives: %s', 'roots'), get_the_date('F Y'));
    } elseif (is_year()) {
      printf(__('Yearly Archives: %s', 'roots'), get_the_date('Y'));
    } elseif (is_author()) {
      printf(__('Author Archives: %s', 'roots'), get_the_author());
    } else {
      single_cat_title();
    }
  } elseif (is_search()) {
    printf(__('Search Results for %s', 'roots'), get_search_query());
  } elseif (is_404()) {
    _e('Not Found', 'roots');
  } else {
    the_title();
  }
}

/** 
 * Relative date formatting
 */
function roots_relative_date($ts)
{
    if(!ctype_digit($ts))
        $ts = strtotime($ts);

    $diff = current_time('timestamp') - $ts;
    if($diff == 0) return __('now', 'relative date', 'roots');
    elseif($diff > 0)
    {
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 60) return _x('just now', 'relative date', 'roots');
            if($diff < 120) return _x('1 minute ago', 'relative date', 'roots');
            if($diff < 3600) return sprintf(_x('%d minutes ago', 'relative date', 'roots'), floor($diff / 60));
            if($diff < 7200) return _x('1 hour ago', 'relative date', 'roots');
            if($diff < 86400) return sprintf(_x('%d hours ago', 'relative date', 'roots'), floor($diff / 3600));
        }
        if($day_diff == 1) return _x('yesterday', 'relative date', 'roots');
        if($day_diff < 7) return sprintf(_x('%d days ago', 'relative date', 'roots'), $day_diff);
        if($day_diff < 31) return sprintf(_x('%d weeks ago', 'relative date', 'roots'), ceil($day_diff / 7));
        if($day_diff < 60) return _x('last month', 'relative date', 'roots');
        return date(_x('F j, Y', 'relative date', 'roots'), $ts);
    }
    else
    {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 120) return _x('in a minute', 'relative date', 'roots');
            if($diff < 3600) return sprintf(_x('in %d minutes', 'relative date', 'roots'), floor($diff / 60));
            if($diff < 7200) return _x('in an hour', 'relative date', 'roots');
            if($diff < 86400) return sprintf(_x('in %d hours', 'relative date', 'roots'), floor($diff / 3600));
        }
        if($day_diff == 1) return _x('tomorrow', 'relative date', 'roots');
        if($day_diff < 4) return date(__('l', 'relative date', 'roots'), $ts);
        if($day_diff < 7 + (7 - date('w'))) return _x('next week', 'relative date', 'roots');
        if(ceil($day_diff / 7) < 4) return sprintf(_x('in %d weeks', 'relative date', 'roots'), ceil($day_diff / 7));
        if(date('n', $ts) == date('n') + 1) return _x('next month', 'relative date', 'roots');
        return date(_x('F j, Y', 'relative date', 'roots'), $ts);
    }
}

function add_filters($tags, $function) {
  foreach($tags as $tag) {
    add_filter($tag, $function);
  }
}

function is_element_empty($element) {
  $element = trim($element);
  return empty($element) ? false : true;
}
