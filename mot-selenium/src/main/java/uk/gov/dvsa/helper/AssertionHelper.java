package uk.gov.dvsa.helper;

public class AssertionHelper {


    public static boolean compareText(String expectedText, String actual) {
        if (expectedText.equals(actual)){
            return true;
        }

        throw new AssertionError("Expected: " + String.format("%s", expectedText)
                + String.format("\n got: %s", actual));
    }

    public static boolean assertValue(boolean expected, boolean actual) {
        if (expected == actual){
            return true;
        }

        throw new AssertionError("Expected: " + expected + "\n got:" + actual);
    }
}
