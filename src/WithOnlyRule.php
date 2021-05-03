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
      $token = $tokens->getCurrent();
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
            $i = 3;
            while (TRUE) {
              if ($tokens->look($i)->getValue() == '{') {
                $curly_counts++;
              }
              if ($tokens->look($i)->getValue() == '}') {
                $curly_counts--;
              }
              $i++;
              if ($curly_counts == 0) {
                break;
              }
            }
            if (!($tokens->look($i)->getValue() == 'only' || $tokens->look($i + 1)->getValue() == 'only')) {
              $violations[] = $this->createViolation($tokens->getSourceContext()->getPath(), $token->getLine(), '0', '"with" should be used along with "only"');
            }
          }
        }
      }
      $tokens->next();
    }

    return $violations;
  }

}
