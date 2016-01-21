package uk.gov.dvsa.domain.model;

public enum VtsChangePageTitle {
    ChangeSiteType("Change site type"),
    ChangeSiteStatus("Change status"),
    ChangeSiteName("Change site name"),
    ChangeSiteClasses("Change classes"),
    ReviewSiteClasses("Review classes"),
    ChangeContactDetailsAddress("Change address"),
    ReviewContactDetailsAddress("Review address"),
    ChangeContactDetailsCountry("Change country"),
    ChangeContactDetailsEmail("Change email address"),
    ChangeContactDetailsTelephone("Change telephone number"),
    ;

    private final String title;

    VtsChangePageTitle(String title) {
        this.title = title;
    }

    public String getText() {
        return title;
    }
}
