package com.dvsa.mot.selenium.datasource;

public class Contact {
    public static final Contact JOHNS_MOTORCYCLE_GARAGE =
            new Contact(Address.JOHNS_MOTORCYCLE_GARAGE, "dummy@email.com", "+768-45-4433630",
                    null);
    public static final Contact POPULAR_GARAGES =
            new Contact(Address.POPULAR_GARAGES, "dummy@email.com", "+768-45-4433630", null);
    public static final Contact TEST_FIT =
            new Contact(Address.TEST_FIT, "dummy@email.com", "+768-45-4433630", null);
    public static final Contact MIKE_AND_JOHN_VTS =
            new Contact(Address.MIKE_AND_JOHN_VTS, "dummy@email.com", "+768-45-4433630", null);
    public static final Contact ANGEL_GARAGES =
            new Contact(Address.ANGEL_GARAGE, "dummy@email.com", "+768-45-4433630", null);
    public static final Contact TEST_FIT_HAS_SLOTS_2 =
            new Contact(Address.TEST_FIT, "dummy@email.com", "+768-45-4433630", null);
    public static final Contact JOHNS_GARAGE =
            new Contact(Address.JOHNS_GARAGE, "dummy@email.com", "+768-45-4433630", null);

    private final Address contactAddress;
    private final String contactEmail;
    private final String contactPhoneNumber;
    private final String contactFaxNumber;

    public Contact(Address contactAddress, String contactEmail, String contactPhoneNumber,
            String contactFaxNumber) {
        super();
        this.contactAddress = contactAddress;
        this.contactEmail = contactEmail;
        this.contactPhoneNumber = contactPhoneNumber;
        this.contactFaxNumber = contactFaxNumber;
    }

    public Address getContactAddress() {
        return contactAddress;
    }

    public String getContactEmail() {
        return contactEmail;
    }

    public String getContactPhoneNumber() {
        return contactPhoneNumber;
    }

    public String getContactFaxNumber() {
        return contactFaxNumber != null ? contactFaxNumber : "";
    }

}
