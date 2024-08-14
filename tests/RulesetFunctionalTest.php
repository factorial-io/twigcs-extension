<?php

namespace FriendsOfTwig\Twigcs\Tests;

use Factorial\twigcs\TwigCsRuleset;
use FriendsOfTwig\Twigcs\Lexer;
use FriendsOfTwig\Twigcs\Rule\RegEngineRule;
use FriendsOfTwig\Twigcs\TwigPort\Source;
use FriendsOfTwig\Twigcs\Validator\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Provides tests for the custom Twigcs ruleset.
 *
 * @author D34dMan <shibinkidd@gmail.com>
 */
class RulesetFunctionalTest extends TestCase {

  /**
   * @dataProvider getData
   */
  public function testExpressions($expression, $violationCount): void {
    $lexer = new Lexer();
    $validator = new Validator();

    $violations = $validator->validate(new TwigCsRuleset(2), $lexer->tokenize(new Source($expression, 'src', 'src.html.twig')));
    $this->assertCount(0, $validator->getCollectedData()[RegEngineRule::class]['unrecognized_expressions'] ?? []);

    if ($violationCount) {
      $this->assertCount($violationCount, $violations, sprintf("There should be %d violation in:\n %s", $violationCount, $expression));
    }
    else {
      $this->assertCount(0, $violations, sprintf("There should be no violations in:\n %s", $expression));
    }
  }

  /**
   * Provides test scenarios.
   */
  public function getData(): array {
    return [
      // NOTE: We expect exactly 17 violations in the fail scenario.
      // In case we add more, don't forget to update violation count.
      [$this->getFailScenarios(), 17],
      [$this->getPassScenarios(), 0],
    ];
  }

  /**
   * Gets failure scenarios.
   */
  public function getFailScenarios() {
    return file_get_contents(__DIR__ . '/assets/fail.twig');
  }

  /**
   * Gets pass scenarios.
   */
  public function getPassScenarios() {
    return file_get_contents(__DIR__ . '/assets/pass.twig');
  }

}
