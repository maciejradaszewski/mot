package uk.gov.dvsa.ui.pages.exception;

import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;

import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class PhpInlineErrorVerifier {

    private static String INLINE_ERROR_REGEXP =
        "(?<inlineError>(?:Fatal error|Notice|Warning|Catchable Fatal error).*on line \\d+)";

    private final static Pattern INLINE_ERROR_PATTERN =
        Pattern.compile(INLINE_ERROR_REGEXP, Pattern.MULTILINE);


    public static void verifyErrorAtPage(MotAppDriver motAppDriver, String pageTitle) {

        Matcher matcher = INLINE_ERROR_PATTERN.matcher(motAppDriver.getPageSource());

        if (matcher.find()) {
            throw new PhpInlineErrorException(
                "The application is showing an inline error: ["
                    + matcher.group("inlineError") + "] on the [" + pageTitle + "] page.");
        }
    }
}
