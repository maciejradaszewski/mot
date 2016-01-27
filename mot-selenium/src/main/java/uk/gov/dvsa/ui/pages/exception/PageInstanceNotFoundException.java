package uk.gov.dvsa.ui.pages.exception;

public class PageInstanceNotFoundException extends RuntimeException {

    public PageInstanceNotFoundException(final String message) {
        super(message);
    }
}
