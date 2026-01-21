#!/bin/bash

# Production Environment Validation Script
# This script validates that the environment is properly configured for production

set -e

echo "🔍 Validating production environment..."

ERRORS=0

# Check APP_ENV
if [ "$APP_ENV" != "production" ]; then
    echo "❌ ERROR: APP_ENV must be 'production' (current: $APP_ENV)"
    ERRORS=$((ERRORS + 1))
else
    echo "✅ APP_ENV is set to 'production'"
fi

# Check APP_DEBUG
if [ "$APP_DEBUG" != "false" ] && [ "$APP_DEBUG" != "0" ]; then
    echo "❌ ERROR: APP_DEBUG must be 'false' in production (current: $APP_DEBUG)"
    ERRORS=$((ERRORS + 1))
else
    echo "✅ APP_DEBUG is set to 'false'"
fi

# Check APP_URL is set and is HTTPS
if [ -z "$APP_URL" ]; then
    echo "❌ ERROR: APP_URL is not set"
    ERRORS=$((ERRORS + 1))
elif [[ ! "$APP_URL" =~ ^https:// ]]; then
    echo "⚠️  WARNING: APP_URL should use HTTPS in production (current: $APP_URL)"
else
    echo "✅ APP_URL is set and uses HTTPS"
fi

# Check database connection
if [ -z "$DB_CONNECTION" ]; then
    echo "❌ ERROR: DB_CONNECTION is not set"
    ERRORS=$((ERRORS + 1))
else
    echo "✅ DB_CONNECTION is set to '$DB_CONNECTION'"
fi

# Check cache driver (should be redis or memcached in production)
if [ -z "$CACHE_DRIVER" ]; then
    echo "⚠️  WARNING: CACHE_DRIVER is not set (defaults to file)"
elif [ "$CACHE_DRIVER" == "file" ]; then
    echo "⚠️  WARNING: CACHE_DRIVER is 'file' - consider using 'redis' or 'memcached' for production"
else
    echo "✅ CACHE_DRIVER is set to '$CACHE_DRIVER'"
fi

# Check queue connection (should be redis or database in production)
if [ -z "$QUEUE_CONNECTION" ]; then
    echo "⚠️  WARNING: QUEUE_CONNECTION is not set (defaults to sync)"
elif [ "$QUEUE_CONNECTION" == "sync" ]; then
    echo "⚠️  WARNING: QUEUE_CONNECTION is 'sync' - consider using 'redis' or 'database' for production"
else
    echo "✅ QUEUE_CONNECTION is set to '$QUEUE_CONNECTION'"
fi

# Check session driver (should be redis or database in production)
if [ -z "$SESSION_DRIVER" ]; then
    echo "⚠️  WARNING: SESSION_DRIVER is not set (defaults to file)"
elif [ "$SESSION_DRIVER" == "file" ]; then
    echo "⚠️  WARNING: SESSION_DRIVER is 'file' - consider using 'redis' or 'database' for production"
else
    echo "✅ SESSION_DRIVER is set to '$SESSION_DRIVER'"
fi

# Check admin email is set
if [ -z "$ADMIN_EMAIL" ]; then
    echo "⚠️  WARNING: ADMIN_EMAIL is not set"
else
    echo "✅ ADMIN_EMAIL is set"
fi

# Summary
echo ""
if [ $ERRORS -eq 0 ]; then
    echo "✅ Production environment validation passed!"
    exit 0
else
    echo "❌ Production environment validation failed with $ERRORS error(s)"
    exit 1
fi

