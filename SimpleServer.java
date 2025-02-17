package api;

import com.sun.net.httpserver.HttpServer;
import com.sun.net.httpserver.HttpHandler;
import com.sun.net.httpserver.HttpExchange;

import java.io.IOException;
import java.io.OutputStream;
import java.io.InputStreamReader;
import java.io.BufferedReader;
import java.net.InetSocketAddress;
import java.util.HashMap;
import java.util.Map;

public class SimpleServer {
	
	private static Map<String, String> users = new HashMap<>();

	public static void main(String[] args) throws Exception {
		
		try {
			HttpServer server = HttpServer.create(new InetSocketAddress(8081), 0);
			server.createContext("/users/login", new LoginHandler());
			server.createContext("/users/register", new RegisterHandler());
			server.setExecutor(null);
			server.start();
			System.out.println("Server started on port 8081");
		} catch(Exception e) {
			System.out.println("Error starting server: " + e.getMessage());
			e.printStackTrace();
		}

	}
	
	static class LoginHandler implements HttpHandler	{
		
		@Override
		public void handle(HttpExchange exchange) throws IOException	{
			
			System.out.println("Request Method: " + exchange.getRequestMethod());
			
			if("POST".equals(exchange.getRequestMethod())) {
				BufferedReader reader = new BufferedReader(new InputStreamReader(exchange.getRequestBody(), "utf-8"));
				StringBuilder requestBody = new StringBuilder();
                String line;
                while ((line = reader.readLine()) != null) {
                    requestBody.append(line);
                }
                reader.close();
                
                System.out.println("Request Body: " + requestBody);
                
                String[] params = requestBody.toString().split("&");
                String username = null, password = null;
                
                for(String param : params) {
                	String[] pair = param.split("=");
                	if(pair.length == 2) {
                		if("username".equals(pair[0])) username = pair[1];
                		if("password".equals(pair[0])) password = pair[1];
                	}
                }
                
                String response;
                if (username != null && password != null && password.equals(users.get(username))) {
                    response = "Login successful!";
                } else {
                    response = "Invalid credentials!";
                }

                exchange.sendResponseHeaders(200, response.getBytes().length);
                OutputStream os = exchange.getResponseBody();
                os.write(response.getBytes());
                os.close();
                
            } else {
                exchange.sendResponseHeaders(405, -1);
                System.out.println("Method not allowed: " + exchange.getRequestMethod());
            }
			
		}
		
		
	}
	
	static class RegisterHandler implements HttpHandler	{
		
		@Override
		public void handle(HttpExchange exchange) throws IOException	{
				
			if("POST".equals(exchange.getRequestMethod())) {
				BufferedReader reader = new BufferedReader(new InputStreamReader(exchange.getRequestBody(), "utf-8"));
				StringBuilder requestBody = new StringBuilder();
					
				String line;
				while((line = reader.readLine()) != null) {
						requestBody.append(line);
				}
				reader.close();
					
				String[] params = requestBody.toString().split("&");
				String username = null, email = null, password = null;
					
				for(String param : params) {
					String[] pair = param.split("=");
						if(pair.length == 2) {
							if("username".equals(pair[0])) username = pair[1];
							if("email".equals(pair[0])) email = pair[1];
							if("password".equals(pair[0])) password = pair[1];
						}
				}
					
				String response;
					if(username != null && email != null && password != null) {
						if(users.containsKey(username)) {
							response = "Username already exists";
						}
						else {
							users.put(username, password);
							response = "Registration successful";
						}
					}
					else {
						response = "Missing fields";
					}
					
					exchange.sendResponseHeaders(200, response.getBytes().length);
					OutputStream os = exchange.getResponseBody();
					os.write(response.getBytes());
					os.close();
				}
				else {
					exchange.sendResponseHeaders(405, -1);
				}
		}
	
	}
}
