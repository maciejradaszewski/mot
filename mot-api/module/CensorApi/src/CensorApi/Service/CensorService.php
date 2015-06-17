<?php

namespace CensorApi\Service;

use DvsaEntities\Repository\CensorPhraseRepository;

/**
 * Class CensorService
 * @package CensorApi\Service
 */
class CensorService
{
    private $censorPhraseRepository;

    /**
     * @param CensorPhraseRepository $censorPhraseRepository
     */
    public function __construct(CensorPhraseRepository $censorPhraseRepository)
    {
        $this->censorPhraseRepository = $censorPhraseRepository;
    }

    /**
     * @param $text
     * @return bool
     */
    public function containsProfanity($text)
    {
        $blacklist = $this->censorPhraseRepository->getBlacklist();

        return !empty($blacklist) ? $this->hasBadPhrases($text, $blacklist) : false;
    }

    private function hasBadPhrases(&$text, array $badPhrases)
    {
        $string = $this->prepareText($text);
        $mutation = self::mutationBase();

        $badPhrasesCount = count($badPhrases);
        $foundPattern = false;
        for ($i = 0; !$foundPattern && $i < $badPhrasesCount; ++$i) {
            $badPhrase = $this->prepareText($badPhrases[$i]);
            $badPhrasePattern = '/\b' . str_ireplace(
                array_keys($mutation),
                array_values($mutation),
                $badPhrase
            ) . '(?:es|s)?\b/si';
            $foundPattern = (1 === preg_match($badPhrasePattern, $string));
        }

        return $foundPattern;
    }

    /**
     * @param $text
     * @return string
     */
    private function prepareText(&$text)
    {
        $res = html_entity_decode(strip_tags($text));
        $reg_arr = [
            '/\s+/'          => ' ',
            '/[[:punct:]]+/' => '',
            '/[_]+/'         => ''
        ];

        return preg_replace(array_keys($reg_arr), array_values($reg_arr), $res);
    }

    private static function mutationBase()
    {
        $mutation = [];
        $mutation['a'] = '(a|4|@|Á|á|À|Â|à|Â|â|Ä|ä|Ã|ã|Å|å|α|Δ|Λ|λ)+';
        $mutation['b'] = '(b|8|\|3|ß|Β|β)+';
        $mutation['c'] = '(c|Ç|ç|¢|€|<|\(|{|©)+';
        $mutation['d'] = '(d|&part;|\|\)|Þ|þ|Ð|ð)+';
        $mutation['e'] = '(e|3|€|È|è|É|é|Ê|ê|∑)+';
        $mutation['f'] = '(f)+';
        $mutation['g'] = '(g|6|9)+';
        $mutation['h'] = '(h)+';
        $mutation['i'] = '(i|!|\||\]\[|]|1|∫|Ì|Í|Î|Ï|ì|í|î|ï)+';
        $mutation['j'] = '(j)+';
        $mutation['k'] = '(k|Κ|κ)+';
        $mutation['l'] = '(l|1|!|\||\]\[|]|£|∫|Ì|Í|Î|Ï)+';
        $mutation['m'] = '(m)+';
        $mutation['n'] = '(n|η|Ν|Π)+';
        $mutation['o'] = '(o|0|Ο|ο|Φ|¤|°|ø|0)+';
        $mutation['p'] = '(p|ρ|Ρ|¶|þ)+';
        $mutation['q'] = '(q)+';
        $mutation['r'] = '(r|®)+';
        $mutation['s'] = '(s|5|\$|§)+';
        $mutation['t'] = '(t|Τ|τ)+';
        $mutation['u'] = '(u|υ|µ)+';
        $mutation['v'] = '(v|υ|ν)+';
        $mutation['w'] = '(w|ω|ψ|Ψ)+';
        $mutation['x'] = '(x|Χ|χ)+';
        $mutation['y'] = '(y|¥|γ|ÿ|ý|Ÿ|Ý)+';
        $mutation['z'] = '(z|Ζ)+';

        return $mutation;
    }
}
