<?php
/**
 * Created by PhpStorm.
 * User: ddiallo
 * Date: 9/23/19
 * Time: 11:50 AM
 */
namespace Drupal\validation_rules_entryform\Feeds\Parser;

use Drupal\feeds\Feeds\Parser\CsvParser;
use Drupal\feeds\Component\CsvParser as CsvFileParser;
use Drupal\feeds\Exception\EmptyFeedException;
use Drupal\feeds\FeedInterface;
use Drupal\feeds\Feeds\Item\DynamicItem;
use Drupal\feeds\Result\FetcherResultInterface;
use Drupal\feeds\Result\ParserResult;
use Drupal\feeds\StateInterface;

/**
 * Defines a CSV feed parser.
 *
 * @FeedsParser(
 *   id = "csv",
 *   title = "CSV",
 *   description = @Translation("Parse CSV files."),
 *   form = {
 *     "configuration" = "Drupal\feeds\Feeds\Parser\Form\CsvParserForm",
 *     "feed" = "Drupal\feeds\Feeds\Parser\Form\CsvParserFeedForm",
 *   },
 * )
 */

class CustomParser extends CsvParser {

  public function parse(FeedInterface $feed, FetcherResultInterface $fetcher_result, StateInterface $state) {

  $importData = [];
  // Get sources.
  $sources = [];
  foreach ($feed->getType()->getMappingSources() as $key => $info) {
    if (isset($info['value']) && trim(strval($info['value'])) !== '') {
      $sources[$info['value']] = $key;
    }
  }

  $feed_config = $feed->getConfigurationFor($this);

  if (!filesize($fetcher_result->getFilePath())) {
    throw new EmptyFeedException();
  }

  // Load and configure parser.
  $parser = CsvFileParser::createFromFilePath($fetcher_result->getFilePath())
    ->setDelimiter($feed_config['delimiter'] === 'TAB' ? "\t" : $feed_config['delimiter'])
    ->setHasHeader(!$feed_config['no_headers'])
    ->setStartByte((int) $state->pointer);

  // Wrap parser in a limit iterator.
  $parser = new \LimitIterator($parser, 0, $this->configuration['line_limit']);

  $header = !$feed_config['no_headers'] ? $parser->getHeader() : [];
  $result = new ParserResult();

  foreach ($parser as $row) {
    $item = new DynamicItem();

    //Get the line values in a tab
    $line = [];
    foreach ($row as $delta => $cell) {
      $key = isset($header[$delta]) ? $header[$delta] : $delta;
      // Pick machine name of source, if one is found.
      if (isset($sources[$key])) {
        $key = $sources[$key];
      }
      $item->set($key, $cell);
      $line[$key] = $cell;
    }
    //Change the status of the item depending on validation rules
    if ($this->testItemValidation($line)) {
      $item->set("validation_status", "Yes");
    }
    else {
      $item->set("validation_status", "No");
    }

    $result->addItem($item);
  }

  // Report progress.
  $state->total = filesize($fetcher_result->getFilePath());
  $state->pointer = $parser->lastLinePos();
  $state->progress($state->total, $state->pointer);

  // Set progress to complete if no more results are parsed. Can happen with
  // empty lines in CSV.
  if (!$result->count()) {
    $state->setCompleted();
  }

  return $result;
}

  /**
   * To verify validation rules
   */
  public function testItemValidation($line) {

    $rules = [["ind1" => "c-newinc", "operator" => "<", "ind2" => "newrel-m014"],
      ["ind1" => "newrel_f014", "operator" => "<=", "ind2" => "e_prevtx_eligible"]
    ];

    $status = FALSE;
    foreach ($rules as $rule) {
      if ($this->comparator($line, $rule)) {
        $status = TRUE;
      }
    }

    return $status;
  }

  public function comparator($item, $rule) {

    if ($rule["operator"] == "<") {
      if ($item[$rule["ind1"]] < $item[$rule["ind2"]]) {
        return true;
      }
      else {
        return false;
      }
    }
    else if ($rule["operator"] == "<=") {
      if ($item[$rule["ind1"]] <= $item[$rule["ind2"]]) {
        return true;
      }
      else {
        return false;
      }
    }
  }

}