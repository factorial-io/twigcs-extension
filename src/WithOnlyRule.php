<?php

namespace Factorial\twigcs;

use FriendsOfTwig\Twigcs\Lexer;
use FriendsOfTwig\Twigcs\Rule\AbstractRule;
use FriendsOfTwig\Twigcs\Rule\RuleInterface;
use FriendsOfTwig\Twigcs\TwigPort\Token;
use FriendsOfTwig\Twigcs\TwigPort\TokenStream;

/**
 * Custom twig cs rule to make sure "with" is used with "only".
 */
class WithOnlyRule extends AbstractRule implements RuleInterface {

  /**
   * @{inheritdoc}
   */
  public function check(TokenStream $tokens) {
    $violations = [];

    while (!$tokens->isEOF()) {
      // We don't check for "with" rule inside "embed" block.
      $this->escapeEmbedBlock($tokens);
      $token = $tokens->getCurrent();
      if ($tokens->isEOF()) {
        break;
      }
      // Check for with rule inside "include" block.
      if ($new_violations = $this->validateWithUsedWithOnly($token, $tokens)) {
        $violations = array_merge($violations, $new_violations);
      }
      $tokens->next();
    }

    return $violations;
  }

  /**
   * Escape embed block.
   */
  protected function escapeEmbedBlock(TokenStream &$tokens) {
    $token = $tokens->getCurrent();
    if (Token::NAME_TYPE === $token->getType() && $token->getValue() == 'embed') {
      while (!$tokens->isEOF()) {
        $token = $tokens->getCurrent();
        if (Token::BLOCK_END_TYPE === $token->getType()) {
          break;
        }
        $tokens->next();
      }
    }
  }

  /**
   * Validator for with followed by only.
   */
  public function validateWithUsedWithOnly(Token $token, TokenStream &$tokens): array
  {
    $violations = [];
    if (Token::NAME_TYPE === $token->getType() && $token->getValue() == 'with') {
      if (Token::WHITESPACE_TYPE === $tokens->look(Lexer::NEXT_TOKEN)->getType()) {
        if (Token::NAME_TYPE === $tokens->look(2)->getType()) {
          if (Token::WHITESPACE_TYPE === $tokens->look(3)->getType() && $tokens->look(4)->getValue() !== 'only') {
            $violations[] = $this->createViolation($tokens->getSourceContext()->getPath(), $token->getLine(), '0', '"with" should be used along with "only"');
          }
        }
        // If it starts "with" and is "{" count till matching "}"
        // and then it next one should be "only".
        elseif ('{' === $tokens->look(2)->getValue()) {
          $curly_counts = 1;
          $look_ahead_position = 3;
          while (TRUE) {
            if ($tokens->look($look_ahead_position)->getValue() == '{') {
              $curly_counts++;
            }
            if ($tokens->look($look_ahead_position)->getValue() == '}') {
              $curly_counts--;
            }
            $look_ahead_position++;
            if ($curly_counts == 0) {
              break;
            }
          }
          if (!($tokens->look($look_ahead_position)->getValue() == 'only' || $tokens->look($look_ahead_position + 1)->getValue() == 'only')) {
            $violations[] = $this->createViolation($tokens->getSourceContext()->getPath(), $token->getLine(), '0', '"with" should be used along with "only"');
          }
        }
      }
    }
    return $violations;
  }

}
