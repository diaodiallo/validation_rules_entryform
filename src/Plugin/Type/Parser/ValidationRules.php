<?php
/**
 * Created by PhpStorm.
 * User: ddiallo
 * Date: 9/18/19
 * Time: 3:32 PM
 */

namespace Drupal\validation_rules_entryform\Plugin\Type\Parser;

use Drupal\feeds\Plugin\Type\Parser\ParserInterface;
use Drupal\feeds\Feeds\Parser\ParserBase;
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
 *   id = "vr",
 *   title = "VR",
 *   description = @Translation("Validate data."),
 *   form = {
 *     "configuration" = "Drupal\feeds\Feeds\Parser\Form\CsvParserForm",
 *     "feed" = "Drupal\feeds\Feeds\Parser\Form\CsvParserFeedForm",
 *   },
 * )
 */

class ValidationRules extends ParserBase {

  /**
   * Parses content returned by fetcher.
   *
   * @param \Drupal\feeds\FeedInterface $feed
   *   The feed we are parsing for.
   * @param \Drupal\feeds\Result\FetcherResultInterface $fetcher_result
   *   The result returned by the fetcher.
   * @param \Drupal\feeds\StateInterface $state
   *   The state object.
   *
   * @return \Drupal\feeds\Result\ParserResultInterface
   *   The parser result object.
   *
   * @todo This needs more documentation.
   */
  public function parse(FeedInterface $feed, FetcherResultInterface $fetcher_result, StateInterface $state) {

    drupal_set_message(json_encode("I am here"));

  }

  /**
   * Declare the possible mapping sources that this parser produces.
   *
   * @return array|false
   *   An array of mapping sources, or false if the sources can be defined by
   *   typing a value in a text field.
   *
   * @todo Get rid of the false return here and create a configurable source
   *   solution for parsers.
   * @todo Add type data here for automatic mappings.
   * @todo Provide code example.
   */
  public function getMappingSources() {

  }


}