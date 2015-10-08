package uk.gov.dvsa.matcher;

import org.hamcrest.Description;
import org.hamcrest.Matcher;
import org.hamcrest.TypeSafeMatcher;
import uk.gov.dvsa.ui.pages.Page;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

public class BreadcrumbMatcher {

    public static Matcher<Page> hasBreadcrumbElements(final String... expectedElements) {
        return hasBreadcrumbElements(Arrays.asList(expectedElements));
    }

    public static Matcher<Page> hasBreadcrumbElements(final List<String> expectedElementList) {
        final List<String> normalizedExpectedList = new ArrayList<>();
        normalizedExpectedList.add("Home");
        normalizedExpectedList.addAll(expectedElementList);

        return new TypeSafeMatcher<Page>() {
            @Override
            public boolean matchesSafely(final Page page) {
                return normalizedExpectedList.equals(page.getBreadcrumb().asList());
            }

            @Override
            public void describeTo(final Description description) {
                description.appendText("should have breadcrumb equal to ")
                           .appendValue(normalizedExpectedList.toString());
            }
        };
    }
}
