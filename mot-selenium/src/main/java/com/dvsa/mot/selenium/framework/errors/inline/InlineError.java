package com.dvsa.mot.selenium.framework.errors.inline;

public class InlineError extends IllegalStateException {

    public InlineError(String errorMessage) {
        super(errorMessage);
    }
}
