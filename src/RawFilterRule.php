<?php

namespace Factorial\twigcs;

use FriendsOfTwig\Twigcs\Lexer;
use FriendsOfTwig\Twigcs\Rule\AbstractRule;
use FriendsOfTwig\Twigcs\Rule\RuleInterface;
use FriendsOfTwig\Twigcs\TwigPort\Token;
use FriendsOfTwig\Twigcs\TwigPort\TokenStream;

/**
 * Custom Twigcs rule to make sure that "raw" filter is justified by a comment.
 */
class RawFilterRule extends AbstractRule implements RuleInterface {

  /**
   * {@inheritdoc}
   */
  public function check(TokenStream $tokens): array {

    $violations = [];

    while (!$tokens->isEOF()) {
      if ($tokens->isEOF()) {
        break;
      }
      $token = $tokens->getCurrent();
      if ($new_violations = $this->validateRawFilterComment($token, $tokens)) {
        $violations = array_merge($violations, $new_violations);
      }
      $tokens->next();
    }

    return $violations;
  }

  /**
   * Validates that any raw filter is preceded by a comment.
   */
  protected function validateRawFilterComment(Token $token, TokenStream &$tokens): array {

    $violations = [];

    // Consider "|raw" filter.
    if ($token->getValue() === 'raw' &&
      Token::NAME_TYPE === $token->getType() &&
      Token::PUNCTUATION_TYPE === $tokens->look(Lexer::PREVIOUS_TOKEN)->getType()
    ) {

      $line = $token->getLine();
      $column = $token->getColumn() + 1;
      $reason = 'usage of the "raw" filter should be justified in a preceding comment.';

      // If the "raw" filter appears on the first line there can not be a
      // preceding comment.
      if ($line === 1) {
        $violations[] = $this->createViolation($tokens->getSourceContext()->getPath(), $line, $column, $reason);
        return $violations;
      }

      // Look behind until the previous line is reached.
      $look_behind = Lexer::PREVIOUS_TOKEN;
      while ($previous_token = $tokens->look($look_behind)) {
        if ($previous_token->getLine() !== $line) {
          break;
        }
        $look_behind--;
      }

      // Check whether the previous line is a comment.
      if ($previous_token->getType() !== Token::COMMENT_TYPE) {
        $violations[] = $this->createViolation($tokens->getSourceContext()->getPath(), $line, $column, $reason);
      }
    }

    return $violations;
  }

}
