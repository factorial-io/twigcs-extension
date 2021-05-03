<?php

namespace Factorial\twigcs;

use FriendsOfTwig\Twigcs\Validator\Violation;
use FriendsOfTwig\Twigcs\Ruleset\Official;

/**
 * Custom twigcs ruleset.
 */
class TwigCsRuleset extends Official {

  /**
   * @{inheritdoc}
   */
  public function getRules() {
    $rules = parent::getRules();
    return array_merge($rules, [
      new WithOnlyRule(Violation::SEVERITY_ERROR),
    ]);
  }

}
