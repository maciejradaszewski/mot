package uk.gov.dvsa.domain.service;

import uk.gov.dvsa.domain.model.User;

import java.io.IOException;

public class SessionManager {

    public static String createSession(User user) throws IOException {
        return new AuthService().createSessionTokenForUser(user);
    }

    public static String createSession(String username, String password) throws IOException {
        return new AuthService().createSessionTokenForUser(username, password);
    }
}
