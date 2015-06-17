package com.dvsa.mot.selenium.datasource;

public class Site {
    public final static Site POPULAR_GARAGES =
            new Site(1, "Popular Garages", "V1234", Contact.POPULAR_GARAGES, null);
    public final static Site JOHNS_GARAGE =
            new Site(2, "Johns Garage", "V11234", Contact.JOHNS_GARAGE, null);
    public final static Site TEST_FIT = new Site(3, "test-fit", "V12345", Contact.TEST_FIT, null);
    public final static Site JOHNS_MOTORCYCLE_GARAGE =
            new Site(11, "Johns Motorcycle Garage", "V123539", Contact.JOHNS_MOTORCYCLE_GARAGE,
                    null);
    public final static Site MIKE_AND_JOHN_VTS =
            new Site(14, "Mike and John VTS", "S000001", Contact.MIKE_AND_JOHN_VTS, null);
    public final static Site ANGEL_GARAGE =
            new Site(13, "Angel Garage", "V123541", Contact.ANGEL_GARAGES, null);
    public final static Site TEST_FIT_HAS_SLOTS_2 =
            new Site(7, "test-fit-has-Slots2", "V12349", Contact.TEST_FIT_HAS_SLOTS_2, null);
    public final static Site FT_GARAGE_1 = new Site(2001, "FT Garage 1", "V1261", null, null);
    public final static Site VENTURE_COMPOUND =
            new Site(17, "Venture Compound", "V880088", null, null);
    public final static Site WELSH_GARAGE =
            new Site(16, "Welsh Garage","V123542",null,null);

    private final int id;
    private final String name;
    private final String number;
    private final Contact contactDetails;
    private final Contact correspondenceDetails;

    public Site(int id, String siteName, String siteNumber, Contact contactDetails,
            Contact correspondenceContactDetails) {
        super();
        this.id = id;
        this.name = siteName;
        this.number = siteNumber;
        this.contactDetails = contactDetails;
        this.correspondenceDetails = correspondenceContactDetails;
    }

    public int getId() {
        return id;
    }

    public String getName() {
        return name;
    }

    public String getNumber() {
        return number;
    }

    public Contact getContactDetails() {
        return contactDetails;
    }

    public Contact getCorrespondenceDetails() {
        return correspondenceDetails;
    }

    public String getNumberAndName() {
        return getNumber() + " - " + getName();
    }

}
