from fastapi import FastAPI, HTTPException
from pydantic import BaseModel, EmailStr
from typing import Optional
import psycopg2
from psycopg2.extras import RealDictCursor
import os
from dotenv import load_dotenv
import bcrypt

load_dotenv()

app = FastAPI()

# Database connection configuration
DB_CONFIG = {
    "dbname": os.getenv("DB_NAME"),
    "user": os.getenv("DB_USER"),
    "password": os.getenv("DB_PASSWORD"),
    "host": os.getenv("DB_HOST"),
    "port": os.getenv("DB_PORT")
}

class UserCreate(BaseModel):
    age: int
    gender: str
    email: EmailStr
    password: str

def get_db_connection():
    return psycopg2.connect(**DB_CONFIG, cursor_factory=RealDictCursor)

@app.post("/api/register")
async def register_user(user: UserCreate):
    try:
        conn = get_db_connection()
        cur = conn.cursor()
        
        # Hash the password
        hashed_password = bcrypt.hashpw(user.password.encode('utf-8'), bcrypt.gensalt())
        
        # Insert the new user
        cur.execute(
            """
            INSERT INTO users (age, gender, email, password)
            VALUES (%s, %s, %s, %s)
            RETURNING id, age, gender, email, created_at
            """,
            (user.age, user.gender, user.email, hashed_password.decode('utf-8'))
        )
        
        new_user = cur.fetchone()
        conn.commit()
        
        return {
            "status": "success",
            "message": "User registered successfully",
            "user": dict(new_user)
        }
        
    except psycopg2.IntegrityError:
        raise HTTPException(status_code=400, detail="Email already exists")
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
    finally:
        if cur:
            cur.close()
        if conn:
            conn.close()

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000) 