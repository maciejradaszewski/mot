package uk.gov.dvsa.domain.service;

import com.jayway.restassured.RestAssured;
import com.jayway.restassured.response.Response;
import org.openqa.selenium.Cookie;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.framework.config.Configurator;

import java.io.IOException;
import java.util.*;

public class CookieService {
    private static AuthService authService = new AuthService();

    public static Cookie generateOpenAmLoginCookie(User user) throws IOException {
        String token = authService.createSessionTokenForUser(user);
       return new Cookie("iPlanetDirectoryPro", token, Configurator.domain(), "/", null);
    }
}
