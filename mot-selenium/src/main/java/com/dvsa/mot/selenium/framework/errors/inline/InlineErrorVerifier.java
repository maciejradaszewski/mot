package com.dvsa.mot.selenium.framework.errors.inline;


import com.dvsa.mot.selenium.framework.BasePage;

import java.util.regex.Matcher;
import java.util.regex.Pattern;


/**
 * Verifies if the page contains inline PHP errors which pollute the page without causing explicit
 * failure of selenium tests. This cause any test to fail if such an error has been found.
 */
public class InlineErrorVerifier {

    private final static String INLINE_ERROR_REGEXP =
            "(?<inlineError>(?:Fatal error|Notice|Warning|Catchable Fatal error).*on line \\d+)";

    private final static Pattern INLINE_ERROR_PATTERN =
            Pattern.compile(INLINE_ERROR_REGEXP, Pattern.MULTILINE);


    public static void verifyInlineErrorAtPage(BasePage page) {

        Matcher matcher = INLINE_ERROR_PATTERN.matcher(page.getPageSource());

        if (matcher.find()) {
            String inlineError = matcher.group("inlineError");
            throw new InlineError(
                    "The application is showing an inline error: [" + inlineError + "] on the ["
                            + page.getPageTitle() + "] page.");
        }
    }
}
