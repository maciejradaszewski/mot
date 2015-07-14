package uk.gov.dvsa.ui.pages.exception;

public class PhpInlineErrorException extends IllegalStateException {

    public PhpInlineErrorException(String errorMessage) {
        super(errorMessage);
    }
}
